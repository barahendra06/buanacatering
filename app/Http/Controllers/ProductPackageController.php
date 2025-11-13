<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use File;
use Storage;
use Excel;
use DB;
use Image;

use App\Product;
use App\ProductCategory;
use App\ProductVariant;
use App\ProductPackage;
use App\ProductPackageDetail;

class ProductPackageController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list', App\Product::class);

        $productPackages = ProductPackage::get();

        $data['productPackages'] = $productPackages;
        $data['request'] = $request->all();
        $data['title'] = "Product Package List";

        return view('product_package.index', $data);
    }

    public function create(Request $request)
    {   
        $this->authorize('create', App\Product::class);

        $products = Product::get();

        $data['title'] = "Product Package Create";
        $data['products'] = $products;

        return view('product_package.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', App\Product::class);
        // $product = Product::where('name', $request->product_name)->first();

        // if($product)
        // {
        //     return redirect()->back()->withErrors('Product is existing, try to create another product name')->withInput();
        // }

        try
        {
            DB::beginTransaction();

            $productPackage = new ProductPackage();
            $productPackage->name = $request->product_package_name;
            $productPackage->description = $request->product_package_description;
            $productPackage->price = $request->product_package_price;
            $productPackage->quantity = $request->product_package_quantity;
            $productPackage->save();

            if (isset($request->image_product_package)) {
                //get the photo data and new path
                $file = $request->image_product_package;
                $folderPath = 'uploads/product_package/' . $productPackage->id . '/';
                $newPath = $folderPath . '/package_' . $productPackage->id  .  '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                    ->resize(1000, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($newPath);

                $productPackage->img_path = $newPath; // upload path
                $productPackage->save();
            }


            $countProductSelected = count($request->products_selected_package);
            
            if($countProductSelected)
            {
                for ($i=0; $i < $countProductSelected; $i++) 
                { 
                    
                    $productPackageDetail = new ProductPackageDetail();
                    $productPackageDetail->catering_product_package_id = $productPackage->id ;
                    $productPackageDetail->catering_product_id = $request->products_selected_package[$i] ;
                    $productPackageDetail->save();
                }
            }
          
            DB::commit();

            return redirect()->route('package-index')->withMessage('Create Package success')->withInput();;
        }
        catch(\Exception $e)
        {
            dd($e);
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again')->withInput();;
        }
    }

    public function print($id)
    {
        $product = Product::find($id);

        $data['product'] = $product;

        return view('product.print', $data);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        
        try
        {
            if($product)
            {
                $productVariants = $product->productVariants;

                foreach($productVariants as $item)
                {
                    $item->delete();
                }

                $product->delete();

                return redirect()->back()->withMessage('Delete Data is Success');
            }
        }
        catch(\Exception $e)
        {
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again');
        }
    }

    public function edit($id)
    {   
        $this->authorize('edit', App\Product::class);

        $products = Product::get();

        $productPackage = ProductPackage::findOrFail($id);
        $productPackageDetail = ProductPackageDetail::where('catering_product_package_id',$productPackage->id)->get();


        $data['products'] = $products;
        $data['productPackage'] = $productPackage;
        $data['productPackageDetail'] = $productPackageDetail;

        $data['title'] = "Product Package Edit";

        return view('product_package.edit', $data);
    }

    public function update($id, Request $request)
    {
        $this->authorize('create', App\Product::class);
        $productPackage = ProductPackage::where('id', $id)->first();

        if(!$productPackage)
        {
            return redirect()->back()->withErrors('Product not found, please try again')->withInput();
        }

        try
        {
            DB::beginTransaction();

            $productPackage->name = $request->product_package_name;
            $productPackage->description = $request->product_package_description;
            $productPackage->price = $request->product_package_price;
            $productPackage->quantity = $request->product_package_quantity;
            $productPackage->save();

            if (isset($request->image_product_package)) {
                //get the photo data and new path
                $file = $request->image_product_package;
                $folderPath = 'uploads/product_package/' . $productPackage->id . '/';
                $newPath = $folderPath . 'package_' . $productPackage->id  .  '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                    ->resize(1000, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($newPath);

                $productPackage->img_path = $newPath; // upload path
                $productPackage->save();
            }

            if (isset($request->products_selected_package)) 
            {
                $countProductSelected = count($request->products_selected_package);
                if($countProductSelected)
                {
                    // hapus package detail 
                    foreach ($productPackage->items as $detail) 
                    {
                        $detail->delete();
                    }

                    // update package baru
                    for ($i = 0; $i < $countProductSelected; $i++) {

                        $productPackageDetail = new ProductPackageDetail();
                        $productPackageDetail->catering_product_package_id = $productPackage->id;
                        $productPackageDetail->catering_product_id = $request->products_selected_package[$i];
                        $productPackageDetail->save();
                    }
                }
            }

            DB::commit();

            return redirect()->back()->withMessage('Edit Package success')->withInput();;
        }
        catch(\Exception $e)
        {
            dd($e);
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again')->withInput();;
        }
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Pastikan image_url bisa diakses dari storage
        $imageUrl = null;
        if ($product->img_path) {
            // Jika kamu simpan di storage/app/public/products/
            $imageUrl = asset( $product->img_path);
        } else {
            $imageUrl = asset('images/no-image.png');
        }

        // Return data dalam format JSON
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => (int) $product->price,
            'image_url' => $imageUrl,
            'description' => $product->description ?? '',
        ]);
    }
}
