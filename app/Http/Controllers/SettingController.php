<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;
use App\Posts;
use App\PointSummary;
use App\Content;
use App\PostCategory;
use App\Challenge;
use App\Province;
use App\Member;
use App\Activity;
use App\User;
use App\Setting;
use App\ContentType;
use App\League;

use DB;
use Cache;
use Mail;
use File;
use Image;
use \Carbon\Carbon;

use App\Events\SettingUpdated;
use Event;

class SettingController extends Controller
{

    public function showPopup($id = null, $slug = null)
    {
        $post = Posts::where('id', $id)->where('slug', $slug)->first();
        $data['post'] = $post;
        return view('popup', $data);
    }

    //show setting form
    public function create()
    {

        // find all active challenge
        $challenges = Challenge::select('name', 'id', 'landing_page_url')->active()->approved()->get();
        // get all setting row
        $settings = Setting::get()->keyBy('id');


        $data['challenges'] = $challenges;
        $data['settings'] = $settings;
        $data['title'] = "Setting";

        return view('setting',$data);
    }

    // update setting
    public function updateSidebar(Request $request)
    {
        if ($request->has('submit_cartoon')) {

            $cartoonImage = Setting::find(SETTING_CARTOON);
            //delete old photo
            if(File::exists($cartoonImage->value))
            {
                File::delete($cartoonImage->value);
            }


            //get the photo data and new path
            $file = $request->file('photoCartoon');
            $folderPath = 'img/cartoon/';
            $cartoonImage->value = $folderPath .'cartoonImage-'.Carbon::now()->timestamp.'.' . $file->getClientOriginalExtension(); // upload path

            // create the directory if its not there, this is a must since intervention did not create the directory automatically
            File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

            // resize and save the uploaded file
            Image::make($file)->save($cartoonImage->value);

            //save new path
            $cartoonImage->save();
        }
        elseif($request->has('delete_cartoon'))
        {
            $cartoonImage = Setting::find(SETTING_CARTOON);
            File::delete($cartoonImage->value);
            $cartoonImage->value="";
            $cartoonImage->save();

        }
        elseif ($request->has('submit_challenge'))
        {
            // CHALLENGE IMAGE
            if($request->file('photoChallenge') )
            {
                $challengeImage = Setting::find(SETTING_CHALLENGE_IMAGE);

                //delete old photo if exist
                if(File::exists($challengeImage->value))
                {
                    File::delete($challengeImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoChallenge');
                $folderPath = 'img/challenge/';
                $challengeImage->value = $folderPath .'challengeImage-'.Carbon::now()->timestamp.'.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($challengeImage->value);

                //save new path
                $challengeImage->save();
            }

            // CHALLENGE LINK
            if ($request->challenge_id)
            {
                $challenge = Challenge::find($request->challenge_id);
                $challengeLink = Setting::find(SETTING_CHALLENGE_LINK);

                // if landing page url exists
                if($challenge->landing_page_url or $challenge->landing_page_url != '')
                {
                    $challengeLink->value = $challenge->landing_page_url;
                }
                else
                {
                    if($challenge->content_type_id == CONTENT_POST)
                    {
                        $challengeLink->value = route('post-create', ['id'=>$request->challenge_id]);
                    }
                    elseif($challenge->content_type_id == CONTENT_GRAPHIC)
                    {
                        $challengeLink->value = route('infographic-create', ['id'=>$request->challenge_id]);
                    }
                    elseif($challenge->content_type_id == CONTENT_PHOTO)
                    {
                        $challengeLink->value = route('photo-create', ['id'=>$request->challenge_id]);
                    }
                }

                $challengeLink->save();
            }

        }
        elseif($request->has('delete_challenge'))
        {
            $challengeImage = Setting::find(SETTING_CHALLENGE_IMAGE);
            File::delete($challengeImage->value);
            $challengeImage->value="";
            $challengeImage->save();

        }
        elseif ($request->has('submit_poll'))
        {

            $pollsImage = Setting::find(SETTING_POLLS);
            //delete old photo
            if(File::exists($pollsImage->value))
            {
                File::delete($pollsImage->value);
            }

            //get the photo data and new path
            $file = $request->file('photoPolls');
            $folderPath = 'img/poll/';
            $pollsImage->value = $folderPath .'pollImage-'.Carbon::now()->timestamp.'.' . $file->getClientOriginalExtension(); // upload path

            // create the directory if its not there, this is a must since intervention did not create the directory automatically
            File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

            // resize and save the uploaded file
            Image::make($file)->save($pollsImage->value);

            //save new path
            $pollsImage->save();
        }
        elseif($request->has('delete_poll'))
        {
            $pollsImage = Setting::find(SETTING_POLLS);
            File::delete($pollsImage->value);
            $pollsImage->value="";
            $pollsImage->save();

        }
        elseif ($request->has('submit_banner_announcement'))
        {

            if($request->file('photoBannerAnnoucement'))
            {
                $bannerAnnouncementImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_IMAGE);

                //delete old photo
                if(File::exists($bannerAnnouncementImage->value))
                {
                    File::delete($bannerAnnouncementImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoBannerAnnoucement');
                $folderPath = 'img/announcement/';
                $bannerAnnouncementImage->value = $folderPath .'bannerDesktop.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($bannerAnnouncementImage->value);

                //save new path
                $bannerAnnouncementImage->save();
            }


            if($request->linkBannerAnnouncement)
            {
                // save banner announcement link
                $bannerAnnouncementLink = Setting::find(SETTING_BANNER_ANNOUNCEMENT_LINK);
                if($bannerAnnouncementLink)
                {
                    $bannerAnnouncementLink->value =  $request->linkBannerAnnouncement;
                    $bannerAnnouncementLink->save();
                }
            }
        }
        elseif($request->has('delete_banner_announcement'))
        {
            //remove banner announcement value
            $bannerAnnouncementImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_IMAGE);
            File::delete($bannerAnnouncementImage->value);
            $bannerAnnouncementImage->value="";
            $bannerAnnouncementImage->save();

            //remove banner announcement link value
            $bannerAnnouncementLink = Setting::find(SETTING_BANNER_ANNOUNCEMENT_LINK);
            $bannerAnnouncementLink->value =  "";
            $bannerAnnouncementLink->save();


        }
        elseif ($request->has('submit_banner_announcement_mobile'))
        {
            if($request->file('photoBannerAnnoucementMobile'))
            {
                $bannerAnnouncementMobileImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_MOBILE);

                //delete old photo
                if(File::exists($bannerAnnouncementMobileImage->value))
                {
                    File::delete($bannerAnnouncementMobileImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoBannerAnnoucementMobile');
                $folderPath = 'img/announcement/';
                $bannerAnnouncementMobileImage->value = $folderPath .'bannerMobile.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($bannerAnnouncementMobileImage->value);

                //save new path
                $bannerAnnouncementMobileImage->save();
            }
        }
        elseif($request->has('delete_banner_announcement_mobile'))
        {
            //remove banner announcement value
            $bannerAnnouncementMobileImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_MOBILE);
            File::delete($bannerAnnouncementMobileImage->value);
            $bannerAnnouncementMobileImage->value="";
            $bannerAnnouncementMobileImage->save();
        }
        elseif ($request->has('submit_banner_announcement2'))
        {

            if($request->file('photoBannerAnnoucement2'))
            {
                $bannerAnnouncementImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_IMAGE2);

                //delete old photo
                if(File::exists($bannerAnnouncementImage->value))
                {
                    File::delete($bannerAnnouncementImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoBannerAnnoucement2');
                $folderPath = 'img/announcement/';
                $bannerAnnouncementImage->value = $folderPath .'bannerDesktop2.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($bannerAnnouncementImage->value);

                //save new path
                $bannerAnnouncementImage->save();
            }


            if($request->linkBannerAnnouncement2)
            {
                // save banner announcement link
                $bannerAnnouncementLink = Setting::find(SETTING_BANNER_ANNOUNCEMENT_LINK2);
                if($bannerAnnouncementLink)
                {
                    $bannerAnnouncementLink->value =  $request->linkBannerAnnouncement2;
                    $bannerAnnouncementLink->save();
                }
            }
        }
        elseif($request->has('delete_banner_announcement2'))
        {
            //remove banner announcement value
            $bannerAnnouncementImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_IMAGE2);
            File::delete($bannerAnnouncementImage->value);
            $bannerAnnouncementImage->value="";
            $bannerAnnouncementImage->save();

            //remove banner announcement link value
            $bannerAnnouncementLink = Setting::find(SETTING_BANNER_ANNOUNCEMENT_LINK2);
            $bannerAnnouncementLink->value =  "";
            $bannerAnnouncementLink->save();
        }
        elseif ($request->has('submit_banner_announcement_mobile2'))
        {
            if($request->file('photoBannerAnnoucementMobile2'))
            {
                $bannerAnnouncementMobileImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_MOBILE2);

                //delete old photo
                if(File::exists($bannerAnnouncementMobileImage->value))
                {
                    File::delete($bannerAnnouncementMobileImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoBannerAnnoucementMobile2');
                $folderPath = 'img/announcement/';
                $bannerAnnouncementMobileImage->value = $folderPath .'bannerMobile2.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($bannerAnnouncementMobileImage->value);

                //save new path
                $bannerAnnouncementMobileImage->save();
            }
        }
        elseif($request->has('delete_banner_announcement_mobile2'))
        {
            //remove banner announcement value
            $bannerAnnouncementMobileImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_MOBILE2);
            File::delete($bannerAnnouncementMobileImage->value);
            $bannerAnnouncementMobileImage->value="";
            $bannerAnnouncementMobileImage->save();
        }
        elseif ($request->has('submit_banner_announcement3'))
        {

            if($request->file('photoBannerAnnoucement3'))
            {
                $bannerAnnouncementImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_IMAGE3);

                //delete old photo
                if(File::exists($bannerAnnouncementImage->value))
                {
                    File::delete($bannerAnnouncementImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoBannerAnnoucement3');
                $folderPath = 'img/announcement/';
                $bannerAnnouncementImage->value = $folderPath .'bannerDesktop3.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($bannerAnnouncementImage->value);

                //save new path
                $bannerAnnouncementImage->save();
            }


            if($request->linkBannerAnnouncement3)
            {
                // save banner announcement link
                $bannerAnnouncementLink = Setting::find(SETTING_BANNER_ANNOUNCEMENT_LINK3);
                if($bannerAnnouncementLink)
                {
                    $bannerAnnouncementLink->value =  $request->linkBannerAnnouncement3;
                    $bannerAnnouncementLink->save();
                }
            }
        }
        elseif($request->has('delete_banner_announcement3'))
        {
            //remove banner announcement value
            $bannerAnnouncementImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_IMAGE3);
            File::delete($bannerAnnouncementImage->value);
            $bannerAnnouncementImage->value="";
            $bannerAnnouncementImage->save();

            //remove banner announcement link value
            $bannerAnnouncementLink = Setting::find(SETTING_BANNER_ANNOUNCEMENT_LINK3);
            $bannerAnnouncementLink->value =  "";
            $bannerAnnouncementLink->save();
        }
        elseif ($request->has('submit_banner_announcement_mobile3'))
        {
            if($request->file('photoBannerAnnoucementMobile3'))
            {
                $bannerAnnouncementMobileImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_MOBILE3);

                //delete old photo
                if(File::exists($bannerAnnouncementMobileImage->value))
                {
                    File::delete($bannerAnnouncementMobileImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoBannerAnnoucementMobile3');
                $folderPath = 'img/announcement/';
                $bannerAnnouncementMobileImage->value = $folderPath .'bannerMobile3.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($bannerAnnouncementMobileImage->value);

                //save new path
                $bannerAnnouncementMobileImage->save();
            }
        }
        elseif($request->has('delete_banner_announcement_mobile3'))
        {
            //remove banner announcement value
            $bannerAnnouncementMobileImage = Setting::find(SETTING_BANNER_ANNOUNCEMENT_MOBILE3);
            File::delete($bannerAnnouncementMobileImage->value);
            $bannerAnnouncementMobileImage->value="";
            $bannerAnnouncementMobileImage->save();
        }
        elseif ($request->has('submit_sidebar_announcement'))
        {

            if($request->file('photoSidebarAnnoucement'))
            {
                $sidebarAnnouncementImage = Setting::find(SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE);

                //delete old photo
                if(File::exists($sidebarAnnouncementImage->value))
                {
                    File::delete($sidebarAnnouncementImage->value);
                }

                //get the photo data and new path
                $file = $request->file('photoSidebarAnnoucement');
                $folderPath = 'img/announcement/';
                $sidebarAnnouncementImage->value = $folderPath .'bannerSidebar.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file
                Image::make($file)->save($sidebarAnnouncementImage->value);

                //save new path
                $sidebarAnnouncementImage->save();
            }


            if($request->linkSidebarAnnouncement)
            {
                // save banner announcement link
                $sidebarAnnouncementLink = Setting::find(SETTING_SIDEBAR_ANNOUNCEMENT_LINK);
                if($sidebarAnnouncementLink)
                {
                    $sidebarAnnouncementLink->value =  $request->linkSidebarAnnouncement;
                    $sidebarAnnouncementLink->save();
                }
            }
        }
        elseif($request->has('delete_sidebar_announcement'))
        {
            //remove sidebar announcement value
            $sidebarAnnouncementImage = Setting::find(SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE);
            File::delete($sidebarAnnouncementImage->value);
            $sidebarAnnouncementImage->value="";
            $sidebarAnnouncementImage->save();

            //remove sidebar announcement link value
            $sidebarAnnouncementLink = Setting::find(SETTING_SIDEBAR_ANNOUNCEMENT_LINK);
            $sidebarAnnouncementLink->value =  "";
            $sidebarAnnouncementLink->save();


        }
        elseif($request->has('submit_popup'))
        {
            $popupID = Setting::find(SETTING_POPUP);
            $popupID->value=$request->linkPopup;
            $popupID->save();

        }
        elseif($request->has('delete_popup'))
        {
            $popupID = Setting::find(SETTING_POPUP);
            $popupID->value="";
            $popupID->save();

        }

        $settings = Setting::get();


        return redirect()->route('setting-create');
    }

    public function updateYoutube(Request $request)
    {
        $youtubes = Setting::whereIn('id', SETTING_YOUTUBE_ARRAY)
                                    ->orderBy('id')
                                    ->get();
        $counter=1;
        foreach ($youtubes as $youtube) {
            $youtube->value = $request->value[$counter];
            $youtube->save();
            $counter++;
        }

        return redirect()->route('setting-create');
    }


    //show setting form
    public function listSetting()
    {
        // get all league row
        $data['leagues'] = League::get();
        // get all setting row
        $data['settings'] = Setting::get()->keyBy('id');

        $data['title'] = "Settings List";

        return view('admin.setting',$data);
    }

    //change scoreboard visibility via ajax
    public function changeScoreboardVisibility(Request $request)
    {
        $league = League::findOrFail($request->id);
        if ($league)
        {
            $league->is_scoreboard_visible = ($request->value == "true")? 1 : 0;
            $result = $league->save();

            //if success update scoreboard value then return 1
            if($result)
            {
                Cache::forget('scoreBoard');
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }

    //change standing sidebar visibility via ajax
    public function changeStandingVisibility(Request $request)
    {

        $league = League::findOrFail($request->id);
        if ($league)
        {
            $league->is_sidebar_visible = ($request->value == "true")? 1 : 0;
            $result = $league->save();

            //if success update sidebar league value then return 1
            if($result)
            {
                Cache::forget('sidebar.visible_league');
                Cache::forget('sidebar.league_season');
                Cache::forget('sidebar.league_standing');
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }

    // update ads
    public function updateAds(Request $request)
    {
        // if any image updated
        if(isset($request->image) && $request->image != '')
        {
            $validatedData = $request->validate([
                'image' => 'mimes:jpeg,bmp,png,gif'
            ]);
            $image = Setting::find($request->image_id);

            if($image)
            {
                //delete old photo
                if(File::exists($image->value))
                {
                    File::delete($image->value);
                }


                //get the photo data and new path
                $file = $request->file('image');
                $folderPath = 'img/banner/';
                $image->value = $folderPath .'image-'.Carbon::now()->timestamp.'.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                /*
                // resize and save the uploaded file
                Image::make($file)->save($image->value);

                //save new path
                $image->save();
                */
                //-------dennis 6/1/18----------------------------
                //do not use image intervention for .gif extension. Copy it instead.

                if ($file->getClientOriginalExtension() == 'gif')
                {
                    $source = $file->getRealPath();
                    $destination = $image->value;
                    copy($source, $destination);
                }
                else
                {
                    Image::make($file)->save($image->value);
                }
                //save new path in db.
                $image->save();
            }
        }

        // if any link updated
        if(isset($request->link))
        {
            $link = Setting::find($request->link_id);

            if($link)
            {
                //if link value not empty
                if($request->link != '')
                {
                    $link->value = $request->link; // set link value

                }
                else
                {
                    $link->value = null; // set link value to null
                }

                //save link
                $link->save();
            }
        }

        return redirect()->route('setting-list');
    }

    // delete ads
    public function deleteAds($image_id, $link_id)
    {
        $image = Setting::find($image_id);

        if($image)
        {
            //delete old photo
            if(File::exists($image->value))
            {
                File::delete($image->value);

                $image->value = null;
                //save new path
                $image->save();
            }
        }

        $link = Setting::find($link_id);

        if($link)
        {
            //delete old link value
            $link->value = null;
            //save new path
            $link->save();
        }
        return redirect()->route('setting-list');
    }
}
