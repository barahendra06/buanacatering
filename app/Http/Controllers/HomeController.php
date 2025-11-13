<?php

namespace App\Http\Controllers;

use Auth;


use Session;

use Cache;
use Mail;
use File;
use Image;
use DB;
use Datatables;
use App\Contact;
use App\Content;
use App\Setting;
use App\Student;
use App\Activity;
use App\District;
use App\Province;
use App\Challenge;
use App\Classroom;
use \Carbon\Carbon;
use App\ContentType;
use App\PhotoGallery;
use App\PointSummary;
use App\PostCategory;
use App\EducationType;
use App\Http\Requests;

use App\TrialRegistration;
use App\StudentRegistration;
use Illuminate\Http\Request;
use App\ExternalModels\School;
use App\RegistrationReference;
use Illuminate\Support\Facades\Input;

use App\ProductCategory;
use App\Product;
use App\ProductPackage;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{    

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $productCategories = ProductCategory::active()->orderBy('sequence', 'asc')->get();

        
        $productPackage = ProductPackage::get();
        $products = Product::with('productCategory', 'productVariants')->get();

        $mappingProduct = $products->groupBy(function ($product) {
            return $product->productCategory->name ?? 'Tanpa Kategori';
        });

        $contact = Contact::first();

        $data['productCategories'] = $productCategories;
        $data['productPackage'] = $productPackage;
        $data['mappingProduct'] = $mappingProduct;
        $data['contact'] = $contact;

        return view('home', $data);
    }

    public function maintenance()
    {
        return view('maintenance');
    }

    public function faq()
    {
        return view('faq');
    }
}