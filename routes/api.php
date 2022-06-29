<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Http\Controllers\api\DeficiencyApiController;
use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\api\CropApiController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});**/


// USER ROUTES FOR ADDING DEFICIENCIES
// need to secure this one.
Route::get('user/app/deficiency/fetch/all', [DeficiencyController::class, 'fetchList']);
Route::get('user/app/deficiency/fetch/single/{id}', [DeficiencyController::class, 'fetchDeficiency']);

Route::group(['namespace' => 'api'], function () {
    Route::post( 'community/add/deficiency/image', array('middleware'=>'cors', 'uses'=>[DeficiencyApiController::class, 'add_new_image'] ));
    Route::get('user/app/crop/fetch/list', array('middleware' => 'cors', 'uses' => [CropApiController::class, 'fetchAppList']));
    Route::get('user/app/deficiency/fetch/list/{id}', array('middleware'=> 'cors', 'uses'=> [DeficiencyApiController::class, 'fetch_list_from_crop_id']));
    Route::get('users/app/deficiency/fetch/single/{id}', array('middleware'=> 'cors', 'uses'=> [DeficiencyApiController::class, 'deficiency_detail']));
    Route::get('users/app/deficiency/exists/{crop_id}/{element_id}', array('middleware' => 'cors', 'uses' => [DeficiencyApiController::class, 'deficiency_exists']));
});

