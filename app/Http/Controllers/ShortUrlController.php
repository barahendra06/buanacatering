<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ShortUrl;

use Form;
use File;
use Datatables;

//alief.mf 20151022 - all API related request from ajax or android apps handled here

class ShortUrlController extends Controller {
	
	public function index($parameter)
	{
		$shortUrl = ShortUrl::where('parameter',$parameter)->first();
		

		if(!$shortUrl)
		{
			abort(404);
		}
		$shortUrl->increment('click');

		return redirect($shortUrl->url_target);
	}	

	public function create()
	{
		$shortUrls = ShortUrl::paginate(10);
		
        $data['title'] = "Short URL";
        $data['shortUrls'] = $shortUrls;
		return view('admin.short_url', $data);
	}	

	public function store(Request $request)
	{
		try 
		{
			$shortUrl = new ShortUrl();
			$shortUrl->parameter = $request->parameter;
			$shortUrl->url_target = $request->url_target;
			$shortUrl->save();

			return redirect()->route('short-url-create')->withMessage("Short URL saved");
		} 
		catch (\Exception $e) 
		{
			$errorCode = $e->errorInfo[1];
            if($errorCode == 1062)
            {
            	return redirect()->route('short-url-create')->withError(["Duplicate parameter. Please change your parameter"]);
            }	
		}
	}


	public function update(Request $request)
	{
		try 
		{
			$shortUrl = ShortUrl::findOrFail($request->id);
			$shortUrl->parameter = $request->parameter;
			$shortUrl->url_target = $request->url_target;
			$shortUrl->save();

			return redirect()->route('short-url-create')->withMessage("Short URL updated");
		} 
		catch (\Exception $e) 
		{
			$errorCode = $e->errorInfo[1];
            if($errorCode == 1062)
            {
            	return redirect()->route('short-url-create')->withError(["Duplicate parameter. Please change your parameter"]);
            }	
		}
	}	

	public function delete($id)
	{
		$shortUrl = ShortUrl::findOrFail($id);
		$shortUrl->delete();

		return redirect()->route('short-url-create')->withMessage("Short URL deleted");
	}	
}

