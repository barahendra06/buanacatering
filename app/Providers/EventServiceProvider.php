<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        //NOTES: Model events are creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored

        // ----------------------- SETTING EVENTS -------------------------
        //setting created or saved
        'eloquent.saved: App\Setting' => [
            'App\Listeners\CacheListener@onSettingSaved',
        ],

        // ----------------------- NOTIFICATION EVENTS -------------------------
        //setting created or saved
        'eloquent.saved: App\Notification' => [
            'App\Listeners\CacheListener@onNotificationSaved',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
