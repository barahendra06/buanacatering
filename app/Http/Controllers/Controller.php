<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function formatPhoneNumber($phoneNumber)
    {
        if(substr($phoneNumber, 0, 3) == "+62")
        {
            // check if string contains +62 at the first of character
            $formatPhoneNumber = $phoneNumber;
        }
        elseif (substr($phoneNumber, 0, 1) == "0")
        {
            // check if string contains 0 at the first of character
            $formatPhoneNumber = '+62'.substr($phoneNumber, 1);
        }
        else
        {
            $formatPhoneNumber = '+62'.$phoneNumber;
        }

        return $formatPhoneNumber;
    }
}
