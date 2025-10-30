<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider 
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */	
    public function boot() 
	{
		view()->composer('*',"App\Http\ViewComposers\Navigation");
    }
	
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }	
}