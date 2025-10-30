<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SitemapController;

class SitemapCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create site sitemap';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SitemapController $sitemapController)
    {
		$sitemapController->store();
    }
}
