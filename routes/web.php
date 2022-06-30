<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\LeadcheckController;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});

/*
Route::get('pruebaBD', function () {
    $sites = DB::table('pages')->get();
 //   $users = DB::table('users')->get();
    return $sites;
});
*/
//Route::post('config_prue', [ConfigController::class, 'store']);
Route::post('check_leads', [LeadcheckController::class, 'store']);
