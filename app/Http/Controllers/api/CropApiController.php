<?php
/**
 * Name: CropApiController
 *
 * Description:
 *
 *
 * Copyright (c) 2018. All Code is the property of Ledgedog unless unless otherwise specified by contract.
 *
 *          (\ /)
 *          (O .o)
 *          (> "<)
 *          (_/\_)
 *      ]) o 0 []v[]
 *
 *
 *
 * @author Michael Rumack
 * @company Ledgedog
 * User: climbican
 * Date: 8/5/19
 * Time: 2:35 PM
 * Last Mod:
 * Notes:  TODO : NEED TO MAKE THE WHOLE IMAGE DYNAMIC, THIS IS TOO MUCH WORK.
 */

namespace App\Http\Controllers\api;

use App\Crop;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class CropApiController{
	public function __construct() {}

	/**
	 * @desc THIS IS SPECIFICALLY LIST FOR DEFICIENCIES
	 * @return false|string
	 */
	public function fetchAppList() {
		$crop_query = 'select DISTINCT (c.id), c.name, c.sub_type as subType, c.image_url as imgUrl from crop c inner join deficiency d on d.crop_id = c.id ORDER BY c.name ASC;';
		$result = DB::select(DB::raw($crop_query));

		return json_encode(['status'=> 200, 'message'=>'crop list', 'cropsList'=>$result]);
	}
}
