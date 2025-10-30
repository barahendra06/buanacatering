<?php

namespace App\Events\Image;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ImageDeleted extends Event
{
    use SerializesModels;

    public $imagePath;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($imagePath)
    {
        $this->imagePath = $imagePath;
    }
}
