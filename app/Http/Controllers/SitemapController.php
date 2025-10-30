<?php namespace App\Http\Controllers;

use App\Content;
use App\Member;
use App\Posts;
use App\PostCategory;
use App\Tag;
use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;

use Illuminate\Http\Request;

use Auth;
use App;
use \Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// note: use true and false for active posts in postgresql database
// here '0' and '1' are used for active posts because of mysql database

class SitemapController extends Controller
{
    public function store()
	{
		// create new sitemap object
		// $sitemap = App::make("sitemap");
		$sitemap = Sitemap::create();

		$now = Carbon::now();

		// add items to the sitemap (url, date, priority, freq)
		// $sitemap->add(route('home'), $now, '1.0', 'daily');
		$sitemap->add(Url::create(route('home'))
				->setPriority(1.0)
				->setLastModificationDate($now)
				->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

		// get all posts from db
		$posts = Posts::whereHas('content', function ($query) {
                                    $query->article()->notDeleted();//find content that doesnt have challenge_id and not deleted
                                })
								->orderBy('created_at', 'desc')->get();

		// add every post to the sitemap
		foreach ($posts as $post)
		{
			// $sitemap->add(route('post-single', [$post->id, $post->slug]), $post->updated_at, 0.2, 'monthly');
			$sitemap->add(Url::create(route('post-single', [$post->id, $post->slug]))
					->setPriority(0.2)
					->setLastModificationDate($now)
					->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
		}

		// generate your sitemap (format, filename)
		// $sitemap->store('xml', 'sitemap');
		$sitemap->writeToDisk('public', 'sitemap.xml');
		// this will generate file sitemap.xml to your public folder

    }

}
