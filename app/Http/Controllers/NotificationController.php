<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Province;
use App\EducationType;
use App\User;
use App\Activity;
use App\Notification;
use App\NotificationType;
use App\PushNotification;
use App\PushNotificationType;
use App\ActivityType;
use App\Member;
use App\Posts;
use App\City;
use App\Branch;
use App\Student;
use App\Jobs\CreatePushNotification;
use App\PhotoGallery;
use App\PushNotificationSummary;
use App\StudentOfTheMonthAlbum;
use App\Invoice;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Carbon\Carbon;
use Excel;
use Response;
use Datatables;

use Log;

use App\Services\NotificationManager;
use App\Services\PushNotificationManager;
use App\PushNotificationTemplate;
use App\NotificationTemplate;

use Auth;


class NotificationController extends Controller
{

    public function getNotification(Request $request, $member_id)
    {
        $notifications = Notification::with('sender')->where('recipient_id', $member_id)
                                    ->take($request->take)
                                    ->skip($request->skip)
                                    ->orderBy('created_at', 'desc')
                                    ->get();


        return response()->json([
                                    "notification" => $notifications
                                ]);
    }

    public function getAllNotifications($member_id)
    {
        $notifications = Notification::with('sender')->where('recipient_id', $member_id)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);

        $data['allNotifications'] = $notifications;
        $data['title'] = "Notifications";
        
        return view('notification.list', $data);
    }

    public function readNotif($member_id, $id)
    {
        $notification = Notification::with('sender')
                                    ->where('recipient_id', $member_id)
                                    ->where('id', $id)
                                    ->first();
                                    
        $notification->is_read = 1;
        $notification->save();

        return response()->json([
                                    "status" => "status"
                                ]);
    }

    public function sendNotification($id)
    {
        $message = NotificationManager::InvalidDataVerification($id);
        return $message;
    }

    public function create()
    {
        $allowedType = [
            PUSH_NOTIFICATION_TYPE_ARRAY['Inbox'],
            PUSH_NOTIFICATION_TYPE_ARRAY['News'], 
            // PUSH_NOTIFICATION_TYPE_ARRAY['Comic'],
            //PUSH_NOTIFICATION_TYPE_ARRAY['Livestream'],
            PUSH_NOTIFICATION_TYPE_ARRAY['Announcement'],
        ];

        $notificationType = PushNotificationType::whereIn('id', $allowedType)->get();

        $today = Carbon::now();

        // Get 10 days ago
        $latest = Carbon::now()->subDays(10);
        /* $article = Posts::whereHas('content', function($query) use ($today, $latest){
                            $query->approved()
                            ->whereBetween('published_at', [$latest, $today]);
                        })
                        ->get(); */

        // $data['article'] = $article->pluck('title', 'id');
        $data['title'] = "Create Notifications";
        $data['notificationTypes'] = $notificationType;
        return view('notification.create', $data);
    }

    public function store(Request $request)
    {        
        $user = Auth::user();
        // check for authentication
        if ($user->can('publish', 'App\Notification')) 
        {
            // if notification type is inbox
            if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['Inbox'])
            {                
                // prepare data for push notification log.
                $data['title'] = $request->title;
                $data['message'] = isset($request->notificationShortMessage) ? $request->notificationShortMessage : $request->notificationMessage;
                $data['inboxMessage'] = isset($request->notificationMessage) ? $request->notificationMessage : '';
                $data['notificationType'] = $request->notificationType;
                $data['url'] =  isset($request->url) ? $request->url : null ;
                $data['imgFile'] = ($request->file('imgPath')) ? $request->file('imgPath') : null;
                $data['recipient_id'] = $request->member;
                $data['created_by'] = Auth::user()->id;

                $notification = NotificationManager::createNotification($data);
            }
            // if Article
            else if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['News'])
            {
                // get article data
                $post = Posts::findOrFail($request->articleId);
                if ($post->isExternalArticle())
                {
                    $url = $post->external_post_url;
                }
                else
                {
                    $url = route('post-single', ['id' => $post->id, 'slug' => $post->slug]);
                }
                
                $dataNotication = [
                    'title' => $request->title ?? $post->title, 
                    'body' => $request->notificationMessage ?? $post->summary,
                    'push_notification_type_id' => PUSH_NOTIFICATION_TYPE_ARRAY['News'],
                    'detail_id' => $post->id,
                    'detail_url' => $url,
                    'additional_url_params' => '?type=headerless&utm_source=app&utm_medium=news_push_notification',
                    'image_path' => asset($post->featured_image_path),
                    'created_by' => $user->id,
                    'content_id' => $post->content_id
                ];

                // send request to url article to create cache nginx
                $uri = $dataNotication['detail_url'].$dataNotication['additional_url_params'];

                $client = new Client();
                $response = $client->request('GET', $uri, ["http_errors" => false, 'verify' => false]);
                $statusCode = $response->getStatusCode();

                if($statusCode != 200)
                {
                    return redirect()->back()->withMessage('Gagal mengirim notifikasi. Pastikan url berita benar');
                }

                $body = $response->getBody();
                $content = json_decode($body->getContents());

                /* // send request to api comment to create cache nginx
                $uri = route('api-index-comment', ['content_id' => $post->content_id]);
                $client = new Client();
                $response = $client->request('GET', $uri, ["http_errors" => false]);
                $statusCode = $response->getStatusCode();
                if($statusCode != 200)
                {
                    return redirect()->back()->withMessage('Gagal mengirim notifikasi. Pastikan url berita benar');
                }
                $body = $response->getBody();
                $content = json_decode($body->getContents()); */
                
                $recipientFilter = $request->recipientFilter; // All, Province, City, School
                $recipientId = isset($request->recipientId) ? $request->recipientId : null;

                return redirect()->back()->withMessage('Notification has been sent');

            }
            // if another type
            else if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['Announcement'])
            {
                $path = null;

                // dd($request->all(), PUSH_NOTIFICATION_TYPE_ARRAY);
                if($request->file('imgPath'))
                {
                    $img = $request->file('imgPath');

                    $extension = $img->getClientOriginalExtension();     

                    $folderPath = 'uploads/notification/';

                    $imagePath = $folderPath . 'img-announcement-' . strToLower($request->title) . '-' . \Carbon\Carbon::today()->format('Ymdhis') . '.' . $extension; // upload path

                    File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                    Image::make($img)->save($imagePath);

                    $path = $imagePath;
                }

                $recipientFilter = $request->recipientFilter; // All, City, Branch
                $dataNotication = [
                    'title' => $request->title,
                    'body' => $request->notificationMessage,
                    'push_notification_type_id' => PUSH_NOTIFICATION_TYPE_ARRAY['Announcement'],
                    'img_path' => $path,
                    'created_by' => $user->id,
                ];
                $recipientId = isset($request->recipientId) ? $request->recipientId : null;

                return redirect()->back()->withMessage('Notification has been sent');

            }
            else
            {
                return redirect()->back()->withMessage('Failed : insert to push notification log table');
            }

            // return back if success
            return redirect()->back()->withMessage('Notification has been sent');
        }
        else
        {
            return redirect()->back()->withMessage('Unauthorized');
        }
    }

    public function storeOld(Request $request)
    {
        // check for authentication
        if (Auth::user()->can('publish', 'App\Notification')) 
        {
            // prepare data for push notification log.
            $data['title'] = $request->title;
            $data['message'] = isset($request->notificationShortMessage) ? $request->notificationShortMessage : $request->notificationMessage;
            $data['inboxMessage'] = isset($request->notificationMessage) ? $request->notificationMessage : '';
            $data['notificationType'] = $request->notificationType;
            $data['url'] =  isset($request->url) ? $request->url : null ;
            $data['imgFile'] = ($request->file('imgPath')) ? $request->file('imgPath') : null;
            $data['recipient_id'] = $request->member;
            $data['created_by'] = Auth::user()->id;

            // if notification type is inbox
            if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['Inbox'])
            {
                $notification = NotificationManager::createNotification($data);
                // if push notification check box is enable
                if (isset($request->pushNotif))
                {
                    // prepare additional data for inbox type
                    $user = User::find($request->member);
                    $data['token'] = isset($user->fcm_token) ? $user->fcm_token : null;
                    $data['detail_id'] = $notification->id;
                    $data['pushNotif'] = $request->pushNotif;
                    $data['member_id'] = $user->id;
                    $data['pushNotificationType'] = PUSH_NOTIFICATION_TYPE_ARRAY['Inbox'];
                    // create push notification log and push to fire base

                    $pushNotification = PushNotificationManager::createPushNotification($data);
                    if($pushNotification != false)
                    {
                        PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Failed : insert to push notification log table');
                    }
                }
            }
            // if Article
            else if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['News'])
            {
                // get article data
                $post = Posts::findOrFail($request->articleId);
                $data['imgFile'] = ($request->file('imgPathNews')) ? $request->file('imgPathNews') : null;
                $recipientFilter = $request->recipientFilter; // All, Province, City, School
                $recipientId = isset($request->recipientId) ? $request->recipientId : null;

                if ($post->isExternalArticle())
                {
                    $data['url'] = $post->external_post_url;
                }
                else
                {
                    $data['url'] = route('post-single', ['id' => $post->id, 'slug' => $post->slug]);
                }

                $data['additional_url_params'] = '?type=headerless&utm_source=app&utm_medium=news_push_notification';
                $data['featured_image_path'] = "uploads/images/".$post->featured_image_path;
                $data['detail_id'] = $post->id;
                $data['created_by'] = $post->editor->id;
                $data['pushNotificationType'] = PUSH_NOTIFICATION_TYPE_ARRAY['News'];
                $data['content_id'] = $post->content_id;

                
                // send request to url article to create cache nginx
                $uri = $data['url'].$data['additional_url_params'];
                $client = new Client();
                $response = $client->request('GET', $uri, ["http_errors" => false]);
                $statusCode = $response->getStatusCode();
                if($statusCode != 200)
                {
                    return redirect()->back()->withMessage('Gagal mengirim notifikasi. Pastikan url berita benar');
                }
                $body = $response->getBody();
                $content = json_decode($body->getContents());

                // send request to api comment to create cache nginx
                $uri = route('api-index-comment', ['content_id' => $post->content_id]);
                $client = new Client();
                $response = $client->request('GET', $uri, ["http_errors" => false]);
                $statusCode = $response->getStatusCode();
                if($statusCode != 200)
                {
                    return redirect()->back()->withMessage('Gagal mengirim notifikasi. Pastikan url berita benar');
                }
                $body = $response->getBody();
                $content = json_decode($body->getContents());

                // send request to api comment to create cache nginx
                $uri = route('api-news-show', [$post->id]);
                $client = new Client();
                $response = $client->request('GET', $uri, ["http_errors" => false]);
                $statusCode = $response->getStatusCode();
                if($statusCode != 200)
                {
                    return redirect()->back()->withMessage('Gagal mengirim notifikasi. Pastikan url berita benar');
                }
                $body = $response->getBody();
                $content = json_decode($body->getContents());

                // send to All
                if ($recipientFilter == PUSH_NOTIFICATION_TO_ALL) 
                {
                    $pushNotification = PushNotificationManager::createPushNotification($data);
                    if($pushNotification != false)
                    {
                        PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Failed : insert to push notification log table');
                    }
                }
                // send to province, city, school
                else
                {
                    // get users token depend on recipient, 1 for All, 2 for province, 3 for city, 4 for School
                    $registration_ids = self::fetchUserFCMTokens($recipientFilter, $recipientId);

                    if (isset($registration_ids) && $registration_ids->count() > 0) 
                    {
                        foreach ($registration_ids as $registration_id) 
                        {
                            $data['registration_ids'] = $registration_id;
                            $pushNotification = PushNotificationManager::createPushNotification($data);
                            if($pushNotification != false)
                            {
                                PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                            }
                            else
                            {
                                return redirect()->back()->withMessage('Failed : insert to push notification log table');
                            }
                        }  
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Recipient not found');
                    }
                }
            }
            // if Video
            else if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['Video'])
            {
                $data['additional_url_params'] = '?utm_source=app&utm_medium=video_push_notification';
                $data['pushNotificationType'] = PUSH_NOTIFICATION_TYPE_ARRAY['Video'];
                $data['detail_id'] = $request->videoId;
                $data['imgFile'] = ($request->file('imgPathVideo')) ? $request->file('imgPathVideo') : null;
                $recipientFilter = $request->recipientFilter; // All, Province, City, School
                $recipientId = isset($request->recipientId) ? $request->recipientId : null;

                // send to All
                if ($recipientFilter == PUSH_NOTIFICATION_TO_ALL) 
                {
                    $pushNotification = PushNotificationManager::createPushNotification($data);

                    if($pushNotification != false)
                    {
                        PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Failed : insert to push notification log table');
                    }
                }
                // send to province, city, school
                else
                {
                    // get users depend on recipient, 1 for All, 2 for province, 3 for city, 4 for School
                    $registration_ids = self::fetchUserFCMTokens($recipientFilter, $recipientId);

                    if (isset($registration_ids) && $registration_ids->count() > 0) 
                    {
                        foreach ($registration_ids as $registration_id) 
                        {
                            $data['registration_ids'] = $registration_id;
                            $pushNotification = PushNotificationManager::createPushNotification($data);
                            if($pushNotification != false)
                            {
                                PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                            }
                            else
                            {
                                return redirect()->back()->withMessage('Failed : insert to push notification log table');
                            }
                        }  
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Recipient not found');
                    }
                }
            }
            // if Comic
            else if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['Comic'])
            {
                // get article data
                $comicChapter = ComicChapter::findOrFail($request->comicChapterId);
                $data['imgFile'] = ($request->file('imgPathNews')) ? $request->file('imgPathNews') : null;
                $recipientFilter = $request->recipientFilter; // All, Province, City, School
                $recipientId = isset($request->recipientId) ? $request->recipientId : null;

                $data['url'] = route('comic-read', [$comicChapter->comicTitle->id, $comicChapter->comicTitle->slug, $comicChapter->id]);
                $data['additional_url_params'] = '?type=headerless&utm_source=app&utm_medium=news_push_notification';
                $data['featured_image_path'] = asset($comicChapter->image_path);
                $data['detail_id'] = $comicChapter->id;
                $data['pushNotificationType'] = PUSH_NOTIFICATION_TYPE_ARRAY['Comic'];
                $data['content_id'] = $comicChapter->content_id;

                // send to All
                if ($recipientFilter == PUSH_NOTIFICATION_TO_ALL) 
                {
                    $pushNotification = PushNotificationManager::createPushNotification($data);
                    if($pushNotification != false)
                    {
                        PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Failed : insert to push notification log table');
                    }
                }
                // send to province, city, school
                else
                {
                    // get users token depend on recipient, 1 for All, 2 for province, 3 for city, 4 for School
                    $registration_ids = self::fetchUserFCMTokens($recipientFilter, $recipientId);

                    if (isset($registration_ids) && $registration_ids->count() > 0) 
                    {
                        foreach ($registration_ids as $registration_id) 
                        {
                            $data['registration_ids'] = $registration_id;
                            $pushNotification = PushNotificationManager::createPushNotification($data);
                            if($pushNotification != false)
                            {
                                PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                            }
                            else
                            {
                                return redirect()->back()->withMessage('Failed : insert to push notification log table');
                            }
                        }  
                        return redirect()->back()->withMessage('Notification has been sent');
                    }
                    else
                    {
                        return redirect()->back()->withMessage('Recipient not found');
                    }
                }
            }
            // if another type
            else if($request->notificationType == PUSH_NOTIFICATION_TYPE_ARRAY['Announcement'])
            {
                $data['pushNotificationType'] = PUSH_NOTIFICATION_TYPE_ARRAY['Announcement'];
                $pushNotification = PushNotificationManager::createPushNotification($data);
                if($pushNotification != false)
                {
                    PushNotificationManager::sendPushNotificationToFirebase($pushNotification);
                    return redirect()->back()->withMessage('Notification has been sent');
                }
                else
                {
                    return redirect()->back()->withMessage('Failed : insert to push notification log table');
                }
            }
            else
            {
                return redirect()->back()->withMessage('Failed : insert to push notification log table');
            }

            // return back if success
            return redirect()->back()->withMessage('Notification has been sent');
        }
        else
        {
            return redirect()->back()->withMessage('Unauthorized');
        }
    }

    public function getMemberAjax(Request $request)
    {
        /* $member = User::where(function ($query) use($request){
                            $query->where('email','like', '%'.$request->queryParameters.'%');
                        })
                        ->orWhereHas('guardians', function ($q) use ($request){
                            $q->where('name', 'like', '%'.$request->queryParameters.'%');
                        }) 
                        ->whereHas('students', function ($q) use ($request){
                            $q->where('name', 'like', '%'.$request->queryParameters.'%');
                        })
                        ->get(); */

        $students = Student::with('user')->where('name', 'like', '%'.$request->queryParameters.'%')->get();

        // $data['member'] = $member;
        $data['students'] = $students;

        return $data;
    }

    public function getRecipientAjax(Request $request)
    {
        /* if ($request->recipient_data == PUSH_NOTIFICATION_TO_PROVINCE) 
        {
            $province = Province::where(function ($query) use($request){
                            $query->where('name','like', '%'.$request->queryParameters.'%');
                        })
                        ->get();
            $data['recipient'] = $province;
        } */
        if($request->recipient_data == PUSH_NOTIFICATION_TO_CITY)
        {
            $city = City::where(function ($query) use($request){
                            $query->where('name','like', '%'.$request->queryParameters.'%');
                        })
                        ->get();
            $data['recipient'] = $city;
        }
        /* elseif($request->recipient_data == PUSH_NOTIFICATION_TO_SCHOOL) 
        {
            $school = School::where(function ($query) use($request){
                            $query->where('name','like', '%'.$request->queryParameters.'%');
                        })
                        ->get();
            $data['recipient'] = $school;
        } */
        elseif($request->recipient_data == PUSH_NOTIFICATION_TO_BRANCH) 
        {
            $branch = Branch::/* where(function ($query) use($request){
                            $query->where('name','like', '%'.$request->queryParameters.'%');
                        })
                        -> */get();
            $data['recipient'] = $branch;
        }

        return $data;
    }

    public function getArticleAjax(Request $request)
    {
        return $data;
    }
}
