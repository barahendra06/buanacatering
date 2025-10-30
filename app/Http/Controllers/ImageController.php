<?php namespace App\Http\Controllers;

use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;

use Illuminate\Http\Request;

use Auth;
use App;
use Cache;
use Image;
use File;
use \Carbon\Carbon;
use Response;

// note: use true and false for active posts in postgresql database
// here '0' and '1' are used for active posts because of mysql database

class ImageController extends Controller
{
    public function thumbnail($size, $path, Request $request)
	{
		$urlData = parse_url($path);
		
		/*
		//for security reason, make sure the image is from our host, if not just show it directly
		if($urlData['host']!='www.'.env('IMAGE_HOST') and $urlData['host']!='beta.'.env('IMAGE_HOST') and $urlData['host']!=env('IMAGE_HOST'))
		{
			return Redirect::to($path);
		}
		
		if($urlData['host']=='beta.'.env('IMAGE_HOST'))
		{
			$path = str_replace('http://beta', "https://www", $path);
		}
		*/

		//check if the original file is exist, if not exist, exit the process
		if(!File::exists($urlData['path']))
		{
			return;
		}

		//set the thumbnail path
		$thumbPath = 'thumbs/'.$size.'/'.$urlData['path'];

		//if exist, simply redirect to the file
		if(File::exists($thumbPath))
		{
			return Redirect::to($thumbPath);
		}
		else  //if it not exist, create the image
		{
			//check if the size parameter is valid
			if($size==IMAGE_TINY)
			{
				$width = IMAGE_TINY_WIDTH;
				$height = IMAGE_TINY_HEIGHT;
			}
			else if($size==IMAGE_SMALL)
			{
				$width = IMAGE_SMALL_WIDTH;
				$height = IMAGE_SMALL_HEIGHT;
			}
			else if($size==IMAGE_MEDIUM)
			{
				$width = IMAGE_MEDIUM_WIDTH;
				$height = IMAGE_MEDIUM_HEIGHT;
			}
			else if($size==IMAGE_LARGE)
			{
				$width = IMAGE_LARGE_WIDTH;
				$height = IMAGE_LARGE_HEIGHT;
			}
			else if($size==IMAGE_EXTRA_LARGE)
			{
				$width = IMAGE_EXTRA_LARGE_WIDTH;
				$height = IMAGE_EXTRA_LARGE_HEIGHT;
			}
			else
			{
				abort('404');
			}
					
			//before creating the flag, we need to check if another instance currently generating the thumbnail
			//if another instance already in progress, we use the original image and not attempt to create the same file
			if (Cache::has($thumbPath)) 
			{
		    	return Redirect::to($path);
			}

			//no other instance creating it yet, so first we set the flag so other instance not doing the same thing
			Cache::add($thumbPath, true, 1);

			// create the directory if its not there, this is a must since intervention did not create the directory automatically
			File::exists(dirname($thumbPath)) or File::makeDirectory(dirname($thumbPath), 0755, true);

			
			// resize and save the uploaded file			
			Image::make($path)
			->resize($width,$height, function($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();})
			->save($thumbPath);	

			//lastly, redirect to the new file
			return Redirect::to($thumbPath);

		}
	}	
}
