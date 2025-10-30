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


class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list', App\Product::class);

        $productCategories = ProductCategory::get();

        $data['productCategories'] = $productCategories;
        $data['request'] = $request->all();
        $data['title'] = "Product Category List";

        return view('product_category.index', $data);
    }

    public function create(Request $request)
    {   
        $this->authorize('create', App\Product::class);

        $productCategories = ProductCategory::get();

        $data['title'] = "Product Category Create";
        $data['productCategories'] = $productCategories;

        return view('product_category.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', App\Product::class);

        // $productCategory = ProductCategory::where('name', $request->product_name)->first();

        // if($productCategory)
        // {
        //     return redirect()->back()->withErrors('Product is existing, try to create another product name')->withInput();
        // }

        try
        {
            DB::beginTransaction();

            $productCategory = new ProductCategory();
            $productCategory->name = $request->product_category_name;
            $productCategory->description = $request->product_category_description;
            $productCategory->save();

         

            DB::commit();

            return redirect()->route('product-category-index')->withMessage('Create Product Category success')->withInput();;
        }
        catch(\Exception $e)
        {
            dd($e);
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again')->withInput();;
        }
    }

    // public function print($id)
    // {
    //     $product = Product::find($id);

    //     $data['product'] = $product;

    //     return view('product.print', $data);
    // }

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

        $productCategories = ProductCategory::findOrFail($id);
       
        $data['productCategories'] = $productCategories;
        $data['title'] = "Product Edit";

        return view('product_category.edit', $data);
    }

    public function update($id, Request $request)
    {
        $this->authorize('create', App\Product::class);

        $productCategory = ProductCategory::where('id', $id)->first();

        if(!$productCategory)
        {
            return redirect()->back()->withErrors('Product not found, please try again')->withInput();
        }

        try
        {
            DB::beginTransaction();

            $productCategory->name = $request->product_category_name;
            $productCategory->description = $request->product_category_description;
            $productCategory->save();

      
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
