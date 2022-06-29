<?php

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
use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\CompatibilityController;
use App\Http\Controllers\SufficiencyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;


Route::get('/copy/data/to/table', [DeficiencyController::class, 'copy_images_to_image_store']);


Route::get('/', function () {
    return redirect('/login');
});

Route::get('create_crop_thumb', [DeficiencyController::class, 'generate_thumbs']);

/**Route::get('/passport', function() {
return view('passport-issue');
});**/

Auth::routes();

Route::get('logout', function(){
    Auth::logout();
    return redirect('/login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
// PRODUCTS
Route::get('admin/product/list', [ProductController::class, 'index']);
Route::get('admin/product/create', [ProductController::class, 'create']);
Route::post('admin/product/save', [ProductController::class, 'save']);
Route::get('admin/product/update/{id}', [ProductController::class, 'updateForm']);
Route::post('admin/product/update/{id}', [ProductController::class, 'update']);
Route::get('admin/product/delete/{id}', [ProductController::class, 'delete']);
Route::get('admin/product/lookup/{id}', [ProductController::class, 'autoComplete']);
Route::post('admin/product/elements/lookup/{id}', [ProductController::class, 'fetchProductElements']);
Route::get('admin/product/all/element/chart/data', [ProductController::class, 'generate_chart_data']);
Route::get('admin/product/json-list', [ProductController::class, 'fetch_all_prod_page']);
//CROPS
Route::get('admin/crop/list', [CropController::class, 'index']);
Route::post('admin/crop/list', [CropController::class, 'fetchList']);
Route::get('admin/crop/create', [CropController::class, 'create']);
Route::post('admin/crop/save', [CropController::class, 'save']);
Route::get('admin/crop/update/{id}', [CropController::class, 'updateForm']);
Route::post('admin/crop/update/{id}', [CropController::class, 'update']);
Route::get('admin/crop/delete/{id}', [CropController::class, 'delete']);
//ELEMENTS
Route::get('admin/element/list',  [ElementController::class, 'index']);
Route::post('admin/element/list', [ElementController::class, 'fetchList']);
Route::get('admin/element/create', [ElementController::class, 'create']);
Route::post('admin/element/save', [ElementController::class, 'save']);
Route::get('admin/element/update/{id}', [ElementController::class, 'updateForm']);
Route::post('admin/element/update/{id}', [ElementController::class, 'update']);
Route::get('admin/element/delete/{id}', [ElementController::class, 'delete']);
Route::get('admin/element/lookup/{id}', [ElementController::class, 'autoComplete']);
//COMPATIBILITY
Route::get('admin/compatibility/list', [CompatibilityController::class, 'index']);
Route::get('admin/compatibility/create', [CompatibilityController::class, 'create']);
Route::post('admin/compatibility/save', [CompatibilityController::class, 'save']);
Route::get('admin/compatibility/update/{id}', [CompatibilityController::class, 'updateForm']);
Route::post('admin/compatibility/update/{id}', [CompatibilityController::class, 'update']);
Route::get('admin/compatibility/delete/{id}', [CompatibilityController::class, 'delete']);
Route::post('admin/compatibility/fetch', [CompatibilityController::class, 'fetchList']);

//DEFICIENCY
Route::get('admin/deficiency/list/{crowdsourced}', [DeficiencyController::class, 'index']);
Route::post('admin/deficiency/list', [DeficiencyController::class, 'fetchList']);
Route::post('admin/deficiency/lookup/{id}', [DeficiencyController::class, 'fetchDeficiency']); // ???
Route::get('admin/deficiency/create', [DeficiencyController::class, 'create']);
Route::post('admin/deficiency/save/{crowd_sourced}', [DeficiencyController::class, 'save']);
Route::get('admin/deficiency/update/{show_community_images}/{id}/{image_id}', [DeficiencyController::class, 'updateForm']);
Route::post('admin/deficiency/update/{id}', [DeficiencyController::class, 'update']);
Route::get('admin/deficiency/delete/{id}',  [DeficiencyController::class, 'delete']);
Route::delete('admin/deficiency/remove/image/{def_id}/{image_id}', [DeficiencyController::class, 'removeImage']);

Route::get('admin/deficiency/community_images', [DeficiencyController::class, 'deficiencyImagelist']);
Route::get('admin/deficiency/community_image/approve/{id}/{fromImageList}',  [DeficiencyController::class, 'approveImage']);
Route::get('admin/deficiency/community_image/delete/{id}',  [DeficiencyController::class, 'remove_community_image']);

// ANALYTICS
Route::get('admin/app/analytics', [StatsController::class, 'index']);
//SUFFICIENCY
Route::get('admin/sufficiency/list', [SufficiencyController::class, 'index']);
Route::get('admin/sufficiency/create', [SufficiencyController::class, 'create']);
Route::post('admin/sufficiency/save', [SufficiencyController::class, 'save']);
Route::get('admin/sufficiency/update/{id}', [SufficiencyController::class, 'updateForm']);
Route::post('admin/sufficiency/update/{id}', [SufficiencyController::class, 'update']);
Route::get('admin/sufficiency/delete/{id}', [SufficiencyController::class, 'delete']);
Route::get('admin/sufficiency/json-list', [SufficiencyController::class, 'fetch_all_suff_page']);
// this one just pulls the data via AJAX
Route::get('admin/sufficiency/json_data/', [SufficiencyController::class, 'fetch_all_suff']);

//UPDATE USER PASSWORD
Route::get('admin/profile/form/{id}', [ProfileController::class, 'updateForm']);
Route::post('admin/profile/update/{id}', [ProfileController::class, 'updateUser']);

//PROFILE ADMIN
Route::get('admin/profile/list', [ProfileController::class, 'index']);
Route::get('admin/profile/create', [ProfileController::class, 'create']);
Route::post('admin/profile/save', [ProfileController::class, 'save']);
Route::get('admin/profile/update/{id}', [ProfileController::class, 'updateForm']);
Route::post('admin/profile/update/{id}', [ProfileController::class, 'update']);
Route::get('admin/profile/delete/{id}', [ProfileController::class, 'delete']);
Route::post('admin/profile/fetch/{id}', [ProfileController::class, 'fetchUser']);
