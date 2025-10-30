<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '12800M');
ini_set('max_execution_time', 21600); //6 hours


use App\Activity;
use Faker\Factory;
use Illuminate\Http\Request;
//use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Exception;
use Hash;
use DB;
use File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MigrationController extends Controller
{

    public $accumulateKeyOk = 0;
    public $accumulateTotal = 0;

    public function __construct()
    {
        // $this->middleware('auth');
    }
}
