<?php
namespace App\Services;

class NewsManager
{
	//function to encode featured image path 
	public static function encodeFeaturedImagePath($featuredImagePath)
	{
		// parse featured image path and get the file name
		$parseFeaturedImagePath = parse_url($featuredImagePath, PHP_URL_PATH);
		$explodedParseFeaturedImagePath = explode('/', $parseFeaturedImagePath);

		// get file name
		$imageFileName = end($explodedParseFeaturedImagePath);

		// if file name contain special characters
		if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $imageFileName))
		{
			// if image contains space, replace with underscores
			if(preg_match('/\s/',$imageFileName))
			{
				// get the path
				$explodedFeaturedImagePath = explode('/', $featuredImagePath);

				// get last index in array
				$lastIndex = array_key_last($explodedFeaturedImagePath);
				
				// replace array remove space in imageFileName
				$explodedFeaturedImagePath[$lastIndex] = str_replace(' ', '%20', $imageFileName);

				// new path
				$newFeaturedImagePath = implode('/', $explodedFeaturedImagePath);
			}
			else
			{
				$newFeaturedImagePath = $featuredImagePath;
			}
		}
		else
		{
			// encode the image
			$encodeImage = rawurlencode($imageFileName);
			
			// get the path
			$explodedFeaturedImagePath = explode('/', $featuredImagePath);

			// get last index in array
			$lastIndex = array_key_last($explodedFeaturedImagePath);
			
			// replace array
			$explodedFeaturedImagePath[$lastIndex] = $encodeImage;

			// new path
			$newFeaturedImagePath = implode('/', $explodedFeaturedImagePath);
		}

		return $newFeaturedImagePath;
	}

}