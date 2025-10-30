<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Month;
use App\Week;

use Datetime;
use Carbon\Carbon;

class WeekController extends Controller
{
    public function generateWeekInYear(Request $request)
    {
        if(isset($request->year) && $request->year != '')
        {
            // Specify the year for which you want to generate the weeks
            $year = $request->year;

            // Create a Carbon instance for the first day of the year
            $startDate = Carbon::createFromDate($year, 1, 1);

            // Create a Carbon instance for the last day of the year
            $endDate = Carbon::createFromDate($year, 12, 31);

            // Get the total number of weeks between the start and end dates
            $totalWeeks = $endDate->diffInWeeks($startDate);

            // Generate the weeks
            $weeks = [];
            for ($i = 0; $i <= $totalWeeks; $i++) {
                $weekStartDate = $startDate->copy()->addWeeks($i)->startOfWeek();
                $weekEndDate = $startDate->copy()->addWeeks($i)->endOfWeek();
                $weeks[] = [
                    'start_date' => $weekStartDate->format('Y-m-d'),
                    'end_date' => $weekEndDate->format('Y-m-d'),
                ];
            }

            $weekExisting = Week::where('year', $year)->first();

            if(!$weekExisting)
            {
                $weekNumber = 1;
                // Output the generated weeks
                foreach ($weeks as $key => $week) 
                {
                    if($key != 0)
                    {
                        $genWeek = new Week();

                        $genWeek->week_number = $weekNumber++;
                        $genWeek->name = 'Week '.$key;
                        $genWeek->shortname = 'W'.$key;

                        $genWeek->start_date = $week['start_date'];
                        $genWeek->end_date = $week['end_date'];
                        $genWeek->month = date_format(new DateTime($week['start_date']), 'n');
                        $genWeek->year = $year;

                        $genWeek->save();
                    }
                }

                $weekGroupByMonths = Week::where('year', $year)->get()->groupBy('month');

                foreach($weekGroupByMonths as $weekGroupByMonth)
                {
                    $number = 1;

                    foreach($weekGroupByMonth as $week)
                    {
                        $num = $number++;

                        $week->name = 'Week '.$num;
                        $week->shortname = 'W'.$num;
                        $week->save();
                    }
                }

                echo 'Success generate weeks';
            }
            else
            {
                echo 'Weeks in year '.$year.' is existing';
            }
        }
        else
        {
            echo 'there is no request year';
        }
    }

    public function getWeekOption(Request $request)
    {
        $weeks = Week::select('id','start_date','end_date')
                    ->where('year', $request->year)
                    ->where('month',$request->month)
                    ->get();

        $weeksArray = [];

        foreach($weeks as $week)
        {
            $weeksArray = [
                'id' => $week->id,
                'start_date' => Carbon::parse($week->start_date)->format('d, m'),
                'end_date' => Carbon::parse($week->end_date)->format('d, m')
            ];

            $weeksConstruct[] = $weeksArray;

        }
        
        return $weeksConstruct;
    }
}
