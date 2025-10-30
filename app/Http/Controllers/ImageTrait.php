<?php namespace App\Http\Controllers;


trait ImageTrait {
	
	
	public function getImageSizeString($path = null)
    {
        try
        {
            if(file_exists($path))
            {
                list($width, $height) = getimagesize(secure_asset($path));
            }
            else
            {
                $width=600;
                $height=600;
            }
        }
        catch(\Exception $e)
        {
            return '600x600';
        }

        return $width . 'x' . $height;
        
    }

    public function imageSize(Array $data)
    {
        $arrayPaths = array();
        foreach ($data as $key=>$value) 
        {
            if(file_exists($value['path']))
            {
                list($width, $height)=getimagesize($value['path']);
                $arrayPath = array();
                $arrayPath = array_add($arrayPath,'width', $width);
                $arrayPath = array_add($arrayPath,'height', $height);
            }
            else
            {
                $arrayPath = array();
                $arrayPath = array_add($arrayPath,'width', 100);
                $arrayPath = array_add($arrayPath,'height', 100);
            }
            array_push($arrayPaths, $arrayPath);
        }
        
        return $arrayPaths;
    }

    public function getYoutubeThumbMq($youtubeUrl)
    {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $youtubeUrl, $youtubeId);
        
        if(isset($youtubeId[1]))
        {
            return "https://i1.ytimg.com/vi/".$youtubeId[1]."/mqdefault.jpg";
        }
        else
        {
            return "https://i1.ytimg.com/vi/null/mqdefault.jpg";
        }

    }
}
