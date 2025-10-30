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

class HseInspectionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list', App\HseElement::class);

        $hseElements = HseElement::get();

        // default drop down
        $hseElementResults = HseElementResult::get();

        if(isset($request->hse_element_id) && $request->hse_element_id != "")
        {   
            $hseElementResults = $hseElementResults->where('hse_element_id', $request->hse_element_id);
        }

        $data['hseElements'] = $hseElements;
        $data['hseElementResults'] = $hseElementResults;
        $data['request'] = $request->all();
        $data['title'] = "HSE Inspection List";

        return view('hse_inspection.index', $data);
    }

    public function create(Request $request)
    {   
        $this->authorize('create', App\HseElement::class);

        $data['title'] = "HSE Inspection Import";
        $data['hseElements'] = HseElement::all();

        return view('hse_inspection.create', $data);
    }

    public function load(Request $request)
    {
        $this->authorize('create', App\HseElement::class);

        $excel = $request->file('excel_file');

        $extension = $excel->getClientOriginalExtension();

        if(!in_array($extension,['xlsx','xls']))
        {
            return redirect()->back()->withErrors('File ('.$extension.') yang anda upload belum sesuai');
        }

        try
        {
            \DB::beginTransaction();

            // Save file first on folder event
            $folderPath = 'uploads/hse-element/';
            $filename = 'report-'.Carbon::now()->format('dmy').'.'.$excel->getClientOriginalExtension();

            $existingFile = $folderPath.'/'.$filename;

            if(File::exists($existingFile) == true)
            {
                File::delete($existingFile);
            }

            // create the directory if its not there, this is a must since intervention did not create the directory automatically
            File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);
            $excel->move(public_path() .'/'. $folderPath, $filename);

            \DB::commit();
        }
        catch(\Exception $e)
        {
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again');
        }

        sleep(3);

        try
        {
            $data = new HseElementImport();
            Excel::import($data, $existingFile);

            $allData = $data->collection;
            $allItems = $data->collection;

            // dd($data);

            $element['hse_element_id'] = $request->hse_element_id;

            foreach ($allData as $key => $data)
            {
                $elementsTemp = [];

                if($key > 0)
                {
                    $submittedAtString = Date::excelToDateTimeObject($data[0])->format('Y-m-d H:i:s');
                    $dateString = Date::excelToDateTimeObject($data[3])->format('Y-m-d');
                    $timeString = Date::excelToDateTimeObject($data[4])->format('H:i:s');

                    $elementsTemp = [
                        'project'=> $data[1],
                        'location' => $data[2],
                        'submitted_at' => $submittedAtString,
                        'date' => $dateString,
                        'time' => $timeString,
                        'facility_number' => $data[5]
                    ];

                    foreach ($allItems as $key => $item)
                    {
                        $temp = [];

                        if($key > 0)
                        {
                            if($item[0] != "" && $item[1] != "")
                            {
                                $a = 5;
                                $z = 5;

                                for($i=5; $i <= 30; $i++)
                                {
                                    $a += 1;

                                    if($allData[0][$a] == "Tindakan Yang Diperlukan / Required Action")
                                    {
                                        break;
                                    }

                                    $temp[] = [
                                        'name' => $allData[0][$a],
                                        'value' => $item[$a],
                                        'comment' => $item[$z+2]
                                    ];

                                    $a += 1;
                                }

                                $elementsTemp['data'] = $temp;
                            }

                            $elementsTemp['required_action'] = $item[34];
                            $elementsTemp['assigned_to'] = $item[35];
                            $elementsTemp['due_date'] = $item[36];
                            $elementsTemp['inspection_team'] = $item[37];
                            $elementsTemp['inspection_team_name'] = $item[38];
                        }
                    }

                    $responses[] = $elementsTemp;
                }
            }

            $element['responses'] = $responses;

            DB::beginTransaction();

            // Save to database
            foreach($element['responses'] as $response)
            {
                $existingElementResult = HseElementResult::where('hse_element_id', $request->hse_element_id)
                                                        ->where('submitted_at', $response['submitted_at'])
                                                        ->first();

                if(!$existingElementResult)
                {
                    $hseElementResult = new HseElementResult();
                    $hseElementResult->hse_element_id = $request->hse_element_id;
                    $hseElementResult->project_name = $response['project'];
                    $hseElementResult->location = $response['location'];
                    $hseElementResult->date = $response['date'] . ' ' . $response['time'];
                    $hseElementResult->facility_number = $response['facility_number'];
                    $hseElementResult->required_action_description = $response['required_action'];
                    $hseElementResult->assigned_to = $response['assigned_to'];
                    $hseElementResult->due_date = $response['due_date'];

                    // Find inspection Team, if not exist will create new
                    $inspectionTeam = HseInspectionTeam::firstOrCreate(
                        ['name' => $response['inspection_team_name']],
                        ['team_name' => $response['inspection_team']]
                    );

                    $hseElementResult->inspection_team_id = $inspectionTeam->id;
                    $hseElementResult->submitted_at = $response['submitted_at'];

                    $hseElementResult->save();

                    foreach($response['data'] as $item)
                    {
                        $cleanedLines = [];

                        $cleanedLines = array_filter(
                            array_map(function ($line) {
                                $line = trim($line);
                                // Hapus angka + titik di awal kalimat, contoh: "1." atau "2. "
                                return preg_replace('/^\d+\.\s*/', '', $line);
                            }, explode("\n", $item['name']))
                        );

                        // Jika perlu reset array keys
                        $cleanedLines = array_values($cleanedLines);

                        $title = $cleanedLines[0];

                        if(isset($cleanedLines[1]))
                        {
                            $subTitle = $cleanedLines[1];
                        }

                        $hseElementInputItem = HseElementInputItem::where('hse_element_id', $request->hse_element_id)
                                                                ->where('title', $title)
                                                                ->first();

                        if(!$hseElementInputItem)
                        {
                            $hseElementInputItem = new HseElementInputItem();
                            $hseElementInputItem->hse_element_id = $request->hse_element_id;
                            $hseElementInputItem->title = $title;

                            if(isset($subTitle))
                            {
                                $hseElementInputItem->sub_title = $subTitle;
                            }

                            $hseElementInputItem->save();
                        }

                        $hseElementResultItem = new HseElementResultItem();
                        $hseElementResultItem->hse_element_result_id = $hseElementResult->id;
                        $hseElementResultItem->hse_element_input_item_id = $hseElementInputItem->id;
                        $hseElementResultItem->comment = $item['comment'];
                        $hseElementResultItem->compliance_status_id = HseComplianceStatus::where('name', $item['value'])->first()->id;
                        $hseElementResultItem->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('hse-inspection-index')->withMessage('Import HSE Element Response success')->withInput();;
        }
        catch(\Exception $e)
        {
            \Log::error($e);
            return view('hse_inspection.index', $data)->withErrors('Something went wrong, please try again');
        }
    }

    public function print($id)
    {
        $hseElementResult = HseElementResult::find($id);

        $data['hseElementResult'] = $hseElementResult;

        return view('hse_inspection.print', $data);
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
        $hseElementResult = HseElementResult::with('hseElementResultItems')->findOrFail($id);

        $data['hseElements'] = HseElement::all();
        $data['inspectionTeams'] = HseInspectionTeam::all();
        $data['hseElementResult'] = $hseElementResult;
        $data['title'] = "HSE Inspection Edit";
        $data['HseComplianceStatuses'] = HseComplianceStatus::all();

        return view('hse_inspection.edit', $data);

    }

    public function update($id, Request $request)
    {
        $hseElementResult = HseElementResult::with('hseElementResultItems')->findOrFail($id);

        try
        {
            DB::beginTransaction();

            $hseElementResult->hse_element_id = $request->hse_element_id;
            $hseElementResult->project_name = strToUpper($request->project_name);
            $hseElementResult->location = strToUpper($request->location);
            $hseElementResult->date = $request->date;
            $hseElementResult->facility_number = strToUpper($request->facility_number);
            $hseElementResult->required_action_description = strToUpper($request->required_action_description);
            $hseElementResult->assigned_to = strToUpper($request->assigned_to);
            $hseElementResult->due_date = strToUpper($request->due_date);

            // Find inspection Team, if not exist will create new
            $inspectionTeam = HseInspectionTeam::firstOrCreate(
                ['name' => strToUpper($request->hse_inspector_name)],
                ['team_name' => strToUpper($request->hse_inspection_team)]
            );

            $hseElementResult->inspection_team_id = $inspectionTeam->id;
            $hseElementResult->save();

            // Update items
            foreach($hseElementResult->hseElementResultItems as $item)
            {
                $item->compliance_status_id = $request->compliance_status_id[$item->id];
                $item->comment = strToUpper($request->comment[$item->id]);
                $item->save();
            }

            DB::commit();

            return redirect()->back()->withMessage('Update Data is Success');
        }
        catch(Exception $e)
        {
            \Log::error($e);
            return redirect()->back()->withErrors('Something went wrong, please try again');
        }
    }
}
