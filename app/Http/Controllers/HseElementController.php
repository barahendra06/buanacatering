<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use File;
use Storage;
use Excel;
use DB;

use App\HseElement;
use App\HseElementInputItem;
use App\HseElementResult;
use App\HseElementResultItem;
use App\HseInspectionTeam;
use App\HseComplianceStatus;

use App\Imports\HseElementImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HseElementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list', App\HseElement::class);

        $hseElements = HseElement::get();

        if(isset($request->hse_element_id) && $request->hse_element_id != "")
        {   
            $hseElements = $hseElements->where('hse_element_id', $request->hse_element_id);
        }

        $data['hseElements'] = $hseElements;
        $data['request'] = $request->all();
        $data['title'] = "HSE Element List";

        return view('hse_element.index', $data);
    }

    public function create(Request $request)
    {   
        $this->authorize('create', App\HseElement::class);

        $data['title'] = "HSE Element Create";

        return view('hse_element.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', App\HseElement::class);

        $hseElement = HseElement::where('sequence', $request->sequence)->first();

        if($hseElement)
        {
            return redirect()->back()->withErrors('HSE Element is existing, try to create another element')->withInput();
        }

        try
        {
            DB::beginTransaction();

            $hseElement = new HseElement();
            $hseElement->sequence = $request->sequence;
            $hseElement->title = $request->title;
            $hseElement->sub_title = $request->sub_title;
            $hseElement->form_type_number = $request->form_type_number;
            $hseElement->form_letter_number = $request->form_letter_number;
            $hseElement->save();

            DB::commit();

            return redirect()->route('hse-element-index')->withMessage('Create HSE Element success')->withInput();;
        }
        catch(\Exception $e)
        {
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again')->withInput();;
        }
    }

    public function print($id)
    {
        $hseElementResult = HseElementResult::find($id);

        $data['hseElementResult'] = $hseElementResult;

        return view('hse_element.print', $data);
    }

    public function delete($id)
    {
        $hseElementResult = HseElementResult::find($id);
        
        try
        {
            if($hseElementResult)
            {
                $hseElementResultItems = $hseElementResult->hseElementResultItems;

                foreach($hseElementResultItems as $item)
                {
                    $item->delete();
                }

                $hseElementResult->delete();

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
        $this->authorize('edit', App\HseElement::class);

        $hseElement = HseElement::findOrFail($id);

        $data['hseElement'] = $hseElement;
        $data['title'] = "HSE Element Edit";

        return view('hse_element.edit', $data);
    }

    public function update($id, Request $request)
    {
        $this->authorize('edit', App\HseElement::class);

        $exisingHseElement = HseElement::where('sequence', $request->sequence)->first();

        if($exisingHseElement)
        {
            return redirect()->route('hse-element-index')->withErrors('HSE Element Number is existing, try another Element Number');
        }

        $hseElement = HseElement::find($id);

        if(!$hseElement)
        {
            return redirect()->route('hse-element-index')->withErrors('HSE Element is not existing, try another element');
        }

        try
        {
            DB::beginTransaction();

            $hseElement->sequence = $request->sequence;
            $hseElement->title = $request->title;
            $hseElement->sub_title = $request->sub_title;
            $hseElement->form_type_number = $request->form_type_number;
            $hseElement->form_letter_number = $request->form_letter_number;
            $hseElement->save();

            DB::commit();

            return redirect()->route('hse-element-index')->withMessage('Update data success');
        }
        catch(\Exception $e)
        {
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again')->withInput();;
        }
    }
}
