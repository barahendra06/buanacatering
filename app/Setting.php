<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ImageTrait;

class Setting extends Model
{
    use ImageTrait;

    protected $table = 'setting';

    //posts table in database
    protected $guarded = [];


    public function getYoutubeIdAttribute()
    {
    	preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $this->value, $youtubeId);

        return isset($youtubeId[1]) ? $youtubeId[1] : null;

    }
}
