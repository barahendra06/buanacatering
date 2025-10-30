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

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list', App\Product::class);

        $products = Product::get();

        if(isset($request->catering_product_category_id) && $request->catering_product_category_id != "")
        {   
            $products = $products->where('catering_product_category_id', $request->catering_product_category_id);
        }

        $data['products'] = $products;
        $data['request'] = $request->all();
        $data['title'] = "Product List";

        return view('product.index', $data);
    }

    public function create(Request $request)
    {   
        $this->authorize('create', App\Product::class);

        $productCategories = ProductCategory::get();

        $data['title'] = "Product Create";
        $data['productCategories'] = $productCategories;

        return view('product.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', App\Product::class);

        $product = Product::where('name', $request->product_name)->first();

        if($product)
        {
            return redirect()->back()->withErrors('Product is existing, try to create another product name')->withInput();
        }

        try
        {
            DB::beginTransaction();

            $product = new Product();
            $product->catering_product_category_id = $request->product_category_id;
            $product->name = $request->product_name;
            $product->description = $request->product_description;
            $product->price = $request->product_price;
            $product->save();

            if(isset($request->image_product))
            {
                //get the photo data and new path
                $file = $request->image_product;
                $folderPath = 'uploads/product/' . $product->id . '/';
                $newPath = $folderPath . '/product_' . $product->id  .  '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                    ->resize(1000, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($newPath);

                $product->img_path = $newPath; // upload path
                $product->save();
            }
            
            if(isset($request->variant))
            {
                foreach ($request->variant as $key => $value) 
                {
                    if(isset($request->image_article[$key]))
                    {
                        $productVariant = new ProductVariant();
                        $productVariant->catering_product_id = $product->id;
                        $productVariant->name = $request->variant[$key];
                        $productVariant->price = $request->price_article[$key] ?? $request->product_price;
                        $productVariant->description = $request->description_article[$key] ?? null;
                        $productVariant->save();

                        if(isset($request->image_article[$key]))
                        {
                            //get the photo data and new path
                            $file = $request->image_article[$key];
                            
                            $folderPath = 'uploads/product' . $product->id . '/';
                            $newPath = $folderPath . '/product_' . $product->id . '_variant_'. $productVariant->id . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                            // create the directory if its not there, this is a must since intervention did not create the directory automatically
                            File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                            // resize and save the uploaded file            
                            Image::make($file)
                                ->resize(1000, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })
                                ->save($newPath);

                            // update user photo path when creating image is successfully
                            $productVariant->img_path = $newPath; // upload path
                            $productVariant->save();
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('product-index')->withMessage('Create Product success')->withInput();;
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

        $product = Product::findOrFail($id);
        $productCategories = ProductCategory::get();

        $data['product'] = $product;
        $data['productCategories'] = $productCategories;
        $data['title'] = "Product Edit";

        return view('product.edit', $data);
    }

    public function update($id, Request $request)
    {
        $this->authorize('create', App\Product::class);
        
        $product = Product::where('id', $id)->first();

        if(!$product)
        {
            return redirect()->back()->withErrors('Product not found, please try again')->withInput();
        }

        try
        {
            DB::beginTransaction();

            $product->catering_product_category_id = $request->product_category_id;
            $product->name = $request->product_name;
            $product->description = $request->product_description;
            $product->price = $request->product_price;
            $product->save();

            if(isset($request->image_product))
            {
                //get the photo data and new path
                $file = $request->image_product;
                $folderPath = 'uploads/product/' . $product->id . '/';
                $newPath = $folderPath . '/product_' . $product->id  .  '.' . $file->getClientOriginalExtension(); // upload path

                // create the directory if its not there, this is a must since intervention did not create the directory automatically
                File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                // resize and save the uploaded file            
                Image::make($file)
                    ->resize(1000, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($newPath);

                $product->img_path = $newPath; // upload path
                $product->save();
            }

            if($product->productVariants->count())
            {
                foreach($product->productVariants as $productVariant)
                {
                    $productVariant->catering_product_id = $product->id;
                    $productVariant->name = $request->variant_existing[$productVariant->id];
                    $productVariant->price = $request->price_article_existing[$productVariant->id] ?? $request->product_price;
                    $productVariant->description = $request->description_article_existing[$productVariant->id] ?? null;
                    $productVariant->save();

                    if(isset($request->image_article_existing[$productVariant->id]))
                    {
                        //get the photo data and new path
                        $file = $request->image_article[$key];
                        
                        $folderPath = 'uploads/product' . $product->id . '/';
                        $newPath = $folderPath . '/product_' . $product->id . '_variant_'. $productVariant->id . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                        // create the directory if its not there, this is a must since intervention did not create the directory automatically
                        File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                        // resize and save the uploaded file            
                        Image::make($file)
                            ->resize(1000, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->save($newPath);

                        // update user photo path when creating image is successfully
                        $productVariant->img_path = $newPath; // upload path
                        $productVariant->save();
                    }
                }
            }
            
            if(isset($request->variant))
            {
                foreach ($request->variant as $key => $value) 
                {
                    if(isset($request->image_article[$key]))
                    {
                        $productVariant = new ProductVariant();
                        $productVariant->catering_product_id = $product->id;
                        $productVariant->name = $request->variant[$key];
                        $productVariant->price = $request->price_article[$key] ?? $request->product_price;
                        $productVariant->description = $request->description_article[$key] ?? null;
                        $productVariant->save();

                        if(isset($request->image_article[$key]))
                        {
                            //get the photo data and new path
                            $file = $request->image_article[$key];
                            
                            $folderPath = 'uploads/product' . $product->id . '/';
                            $newPath = $folderPath . '/product_' . $product->id . '_variant_'. $productVariant->id . uniqid() . '.' . $file->getClientOriginalExtension(); // upload path

                            // create the directory if its not there, this is a must since intervention did not create the directory automatically
                            File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);

                            // resize and save the uploaded file            
                            Image::make($file)
                                ->resize(1000, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })
                                ->save($newPath);

                            // update user photo path when creating image is successfully
                            $productVariant->img_path = $newPath; // upload path
                            $productVariant->save();
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->back()->withMessage('Edit Product success')->withInput();;
        }
        catch(\Exception $e)
        {
            dd($e);
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again')->withInput();;
        }
    }
}
