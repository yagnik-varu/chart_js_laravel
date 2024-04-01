<?php

use App\Http\Controllers\ChartController;
use App\Models\Asset;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/charts', [ChartController::class,'chartIndex'])->name('chart.index');
Route::get('/chart/year', [ChartController::class,'chartYearView'])->name('chart.year.view');
Route::get('/chart/year/data', [ChartController::class,'chartYearData'])->name('chart.year.data');
Route::get('/chart/month', [ChartController::class,'chartMonthView'])->name('chart.month.view');
Route::get('/chart/month/data', [ChartController::class,'chartMonthData'])->name('chart.month.data');
Route::get('/chart/month/sport/data', [ChartController::class,'chartMonthSportData'])->name('chart.month.sport.data');
Route::get('getData/from/author/{author}',[ChartController::class,'allData'] )->name('allData');
