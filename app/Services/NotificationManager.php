<?php
namespace App\Services;

use App\Invoice;
use App\Notification;
use App\NotificationTemplate;
use App\User;
use App\InvoiceManual;
use App\Services\PushNotificationManager;
use File;
use Image;

use Carbon\Carbon;

class NotificationManager
{
    static function UserRegistered(User $user)
    {
        // prepare message body for notification from notification template
        // get notification template
        $notificationTemplate = NotificationTemplate::find(NOTIFICATION_TEMPLATE_REGISTERED_USER);
        $messageTemplate = $notificationTemplate->message;

        // replace name on template
        $messageTemplate = str_replace('@name@', $user->first_name, $messageTemplate);

        // create notification
        $notification = new Notification();
        $notification->recipient_id = $user->id;
        $notification->notification_type_id = NOTIFICATION_TYPE_ARRAY['Inbox'];
        $notification->sender_id = ADMIN_SENDER_NOTIFICATION;
        $notification->created_by = ADMIN_SENDER_NOTIFICATION;
        $notification->title = $notificationTemplate->title;
        $notification->text = $messageTemplate;
        $notification->date = Carbon::now();
        $notification->save();
    }

    static function InvoiceCreated(Invoice $invoice, $sender = null)
    {
        $notificationTemplate = NotificationTemplate::find(NOTIFICATION_TEMPLATE_INVOICE_CREATED);

        $title = $notificationTemplate->title;
        $message = $notificationTemplate->message;

        $invoiceItems = $invoice->invoiceItems()->whereNotNull('subscription_id')->get();

        // if student invoice more than 1 month subs
        if($invoiceItems->count() > 1)
        {
            $parseStartDate = Carbon::parse($invoiceItems->first()->start_date);
            $parseEndDate = Carbon::parse($invoiceItems->last()->end_date);

            $period = $parseStartDate->format('F') . ' - ' . $parseEndDate->format('F Y');
        }
        else
        {
            $parseDate = Carbon::parse($invoiceItems->last()->end_date);

            $period = $parseDate->format('F Y');
        }

        $student = $invoice->student;
        $parent = $student->user->guardians->first()->name;

        $title = $notificationTemplate->title;
        $title = str_replace('@period@', $period, $title);

        $message = str_replace('@period@', $period, $message);
        $message = str_replace('@student@', ucWords(strToLower($student->name)), $message);

        // Note: Perlu data deep link ke detail invoice terkait
        $data = [
            'recipient_id' => $student->user->id,
            'created_by' => $sender ? $sender->id : ADMIN_SENDER_NOTIFICATION,
            'title' => $title,
            'inboxMessage' => $message,
            'link' => route('api-invoice-show', $invoice->id),
            'button_text' => 'Payment here',
        ];

        $notification = self::createNotification($data);
    }

    static function InvoiceReceiptUploaded(Invoice $invoice)
    {
        $notificationTemplate = NotificationTemplate::find(NOTIFICATION_TEMPLATE_INVOICE_RECEIPT_UPLOADED);
        $messageTemplate = $notificationTemplate->message;

        $competitionName = $invoice->teamRegistration->team->competition->name;
        $messageTemplate = str_replace('@competition@', $competitionName, $messageTemplate);

        $title = $notificationTemplate->title;
        $data = [
            'recipient_id' => $invoice->user->id,
            'created_by' => ADMIN_SENDER_NOTIFICATION,
            'title' => $title,
            'inboxMessage' => $messageTemplate
        ];

        $notification = self::createNotification($data);
    	if (isset($notification->recipient->fcm_token) && $notification->recipient->fcm_token != null) {
            	PushNotificationManager::InvoiceReceiptUploaded($notification);
    	}
    }

    static function createNotification($data)
    {
        try 
        {
            // prepare notification 
            $notification = new Notification();
            $notification->notification_type_id = NOTIFICATION_TYPE_ARRAY['Inbox'];
            $notification->recipient_id = $data['recipient_id'];
            $notification->sender_id = ADMIN_SENDER_NOTIFICATION;
            $notification->created_by = $data['created_by'];
            $notification->title = $data['title'];
            $notification->text = $data['inboxMessage'];
            $notification->date = Carbon::now();
            if(isset($data['link']))
            {
                $notification->link = $data['link'];
            }
            if(isset($data['button_text']))
            {
                $notification->button_text = $data['button_text'];
            }
            if(isset($data['imgFile']))
            {
                //get the photo data and new path
                $file = $data['imgFile'];
                //$folderPath = 'uploads/' . $member->id . '/';
                $folderPath = 'uploads/notification/img/member/'.$data['recipient_id'].'/';

                $newImgPath = $folderPath . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                        ->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->save($newImgPath);

                $oldImgPath = $notification->img_path;
                File::delete(public_path() . '/' . $oldImgPath);
                $notification->featured_image_path =$newImgPath;
            }
            
            $notification->save();
            return $notification;
        }
        catch (Exception $e)
        {
                $message = class_basename( $e ) . ' in ' . basename( $e->getFile() ) . ' line ' . $e->getLine() . ': ' . $e->getMessage();
                Log::error($message);                
                return redirect()->back()->withMessage('Gagal memproses data notifikasi');
        }
    }

    static function sendEmailConfirmation(User $user)
    {
        // prepare message body for notification from notification template
        // get notification template
        $notificationTemplate = NotificationTemplate::find(NOTIFICATION_TEMPLATE_REGISTERED_USER_EMAIL_CONFIRMATION);
        $messageTemplate = $notificationTemplate->message;

        // replace name on template
        $messageTemplate = str_replace('@name@', $user->first_name, $messageTemplate);
        $messageTemplate = str_replace('@alamat_email@', $user->email, $messageTemplate);


        // create notification
        $notification = new Notification();
        $notification->recipient_id = $user->id;
        $notification->notification_type_id = NOTIFICATION_TYPE_ARRAY['Inbox'];
        $notification->sender_id = ADMIN_SENDER_NOTIFICATION;
        $notification->created_by = ADMIN_SENDER_NOTIFICATION;
        $notification->title = $notificationTemplate->title;
        $notification->text = $messageTemplate;
        $notification->date = Carbon::now();
        $notification->save();
    }
}
?>