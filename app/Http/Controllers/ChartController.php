<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChartController extends Controller
{
    
    public function chartIndex(){
        $authors=AssetUser::has('blogs')->get();
        return view('charts',compact('authors'));
    }

    public function allData($author,Request $request){
        DB::enableQueryLog();
        
        $allData=Asset::where('created_by',$author)
                        ->select('sport',DB::raw('count(*) as total'))     
                        ->groupBy('sport')
                        ->whereNotNull('sport')
                        ->orderBy('sport')
                        ->when($request->from ,function($query) use($request){
                            return $query->where('published_date','>=',$request->from);
                        })
                        ->when($request->to ,function($query) use($request){
                            return $query->where('published_date','<=',$request->to);
                        })->get();
        $labels = $allData->pluck('sport')->toArray();
        $value = $allData->pluck('total')->toArray();
        DB::getQueryLog();
        // DB::disableQueryLog();
        
        // if(!empty($value)){
        //     $bandwith = max($value)/100;
            
        //     // return $bandwith;
        // // $count = count($value);
        // // $average = $sum / $count;
        // $other=0;
        // foreach($value as $key=>$total){
        //     if($total<$bandwith){
        //         $other = $other +$total;
        //         unset($value[$key]);
        //         unset($labels[$key]);
        //     }
        // }
        // array_push($labels,'other');
        // array_push($value,$other);

        // }
        
        

        $data=['label'=>array_values($labels),'value'=>array_values($value)];
        return response()->json([
            'status'=>'success',
            'data'=>$data
        ]);
        
    }

    public function chartYearView(){
        $years = Asset::selectRaw('YEAR(published_date) as published_year')->whereNotNull('published_date')->groupBy('published_year')->get();
        return view('yearcompare',compact('years'));
    }

    public function chartYearData(Request $request){

        if (!$request->has('year1') || !$request->has('year2')) {
            return response()->json(['error' => 'Please provide both year1 and year2.'], 400);
        }
        
        $sport = DB::table('assets')
            ->select('sport')
            ->whereNotNull('sport')
            ->where('sport', '<>', '')
            ->orderBy('sport')
            ->distinct()
            ->pluck('sport');

        $yearsData = Asset::selectRaw('YEAR(published_date) as published_year, sport, count(*) as total')
            ->whereNotNull('published_date')
            ->where('sport', '<>', '')
            ->where(function ($query) use ($request) {
            $query->whereYear('published_date', $request->year1)
                ->orWhereYear('published_date', $request->year2);
                })
            ->groupBy('published_year', 'sport')
            ->orderBy('sport')
            ->get();

        $year1Data = $yearsData->filter(function ($item) use ($request) {
            return $item->published_year == $request->year1;
        });

        $year2Data = $yearsData->filter(function ($item) use ($request) {
            return $item->published_year == $request->year2;
        });

        $labels1 = $year1Data->pluck('sport')->toArray();
        $labels2 = $year2Data->pluck('sport')->toArray();
 
        $mergedSports = $sport->merge($labels1)->merge($labels2)->unique()->values()->toArray();
        $mergedValues1 = [];
        $mergedValues2 = [];
        foreach ($mergedSports as $key => $value) {
            $mergedValues1[$key] = $year1Data->firstWhere('sport', $value) ? $year1Data->firstWhere('sport', $value)->total : 0;
            $mergedValues2[$key] = $year2Data->firstWhere('sport', $value) ? $year2Data->firstWhere('sport', $value)->total : 0;
        }
        
        return response()->json([
            'label1' => $request->year1,
            'value1' => $mergedValues1,
            'label2' => $request->year2,
            'value2' => $mergedValues2,
            'labels' => $mergedSports,
        ]);
    }

    


    public function chartMonthView(Request $request){
        $assets = DB::table('assets')
            ->select('sport')
            ->whereNotNull('sport')
            ->where('sport', '<>', '')
            ->orderBy('sport')
            ->distinct()
            ->get();
        $years = Asset::selectRaw('YEAR(published_date) as published_year')->whereNotNull('published_date')->groupBy('published_year')->get();
        return view('monthcompare',compact('years','assets'));
    }

    public function chartMonthData(Request $request){
        $monthData = Asset::selectRaw('MONTH(published_date) as published_month,count(*) as total')
            ->whereNotNull('published_date')->whereNotNull('sport');
        if(($request->has('year')) && ($request->year != 'all')){
            $monthData = $monthData->where(function ($query) use ($request) {
                $query->whereYear('published_date', $request->year);    
            });
        };

        if(($request->has('sport')) && ($request->sport != 'all')){
            $monthData = $monthData->where(function ($query) use ($request) {
                $query->where('sport', $request->sport);    
            });
        };
            
        $monthData=$monthData->groupBy('published_month')
            ->orderBy('published_month')->get();

        $monthIndex = $monthData->pluck('published_month')->toArray();
    
        $label = array_map(function($month) {
            return Carbon::create()->month($month)->format('F');
        }, $monthIndex);
        $value = $monthData->pluck('total')->toArray();
        return response()->json([
            'status'=>'success',
            'label' => $label,
            'value' => $value,
        ]);


    }

    public function chartMonthSportData(Request $request){
        
        $data = Asset::selectRaw('sport,count(*) as total')->whereNotNull('published_date')->whereNotNull('sport')->groupBy('sport');
        $title = "All Blogs of ".$request->month;
        if(($request->has('year')) && ($request->year != 'all')){
            $data = $data->where(function ($query) use ($request) {
                $query->whereYear('published_date', $request->year);    
            });
            $title = $title.' '.$request->year;
        };

        if(($request->has('month'))){
            $data = $data->where(function ($query) use ($request) {
                $query->whereMonth('published_date', date_parse($request->month)['month']);    
            });
        };

        $data = $data->get();
        $label = $data->pluck('sport')->toArray();
        $value = $data->pluck('total')->toArray();
    
        
        
        if($data){
            return response()->json(["status"=>'success',"label"=>$label,"value"=>$value,"month"=>$request->month,'title'=>$title,'sum'=>array_sum($value)])->setStatusCode(200);
        }else{
            return response()->json(["status"=> "fail"])->setStatusCode(404);
        }
    }

    public function yearMonthView(){
        $years = Asset::selectRaw('YEAR(published_date) as published_year')->whereNotNull('published_date')->groupBy('published_year')->get();
        $months = Asset::selectRaw('Month(published_date) as published_month')->whereNotNull('published_date')->groupBy('published_month')->orderBy('published_month')->pluck('published_month')->toArray();
        $monthName = [];
        foreach($months as $month){
            $monthName[$month] = Carbon::create()->month($month)->format('F'); 
        }
        return view('yearMonth', compact('years','monthName'));
    }

    public function yearMonthData(Request $request){
        $data = Asset::selectRaw('count(*) as total,YEAR(published_date) as published_year,Month(published_date) as published_month,Date(published_date) as Date')->whereNotNull('published_date')->whereNotNull('sport')->groupBy('published_year','published_month','Date',);
        // dd($data);
        // if(($request->has('year')) && ($request->year != 'all')){
            $data = $data->where(function ($query) use ($request) {
                $query->whereYear('published_date', $request->year);    
            });
            $data = $data->where(function ($query) use ($request) {
                $query->whereMonth('published_date', $request->month)->get();    
            });
            // $title = $title.' '.$request->year;
        // };
        $label = $data->pluck('Date')->toArray();
        $value = $data->pluck('total')->toArray();
        // return $data->get();
        // return $request->all();
        return response()->json(["status"=>"success","label"=>$label,"value"=>$value]);
    }

    public function specificDateSportData(Request $request){
        $data = Asset::whereDate('published_date', $request->date)->selectRaw('sport,count(*) as total')->groupBy('sport')->get();
        $label = $data->pluck('sport')->toArray();
        $value = $data->pluck('total')->toArray();
        $title = "Blog Published on date ".$request->date;
        return response()->json(["status"=>"success","label"=>$label,"value"=>$value,"title"=>$title]);
    }

   
   
    
    
         

    
}
