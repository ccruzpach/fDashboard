<?php

use App\Http\Controllers\DashboardsController;
use Illuminate\Support\Facades\Route;
// require_once app_path('Services/6.getIndustryInformation.php');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });

Route::get('/', function () {
    return view('dashboard');
});

Route::get('allfillings', function () {
    return getAllFillingsListByCompany('320193', 20150101);
})->name('allFillings');

Route::get('sicdata', function () {
    $url = 'https://www.sec.gov/corpfin/division-of-corporation-finance-standard-industrial-classification-sic-code-list';

    return getSICData($url);
})->name('sicdata');

Route::get('industrycompanies', function () {
    return getsCompanyListByIndustry();
})->name('industrycompanies');
