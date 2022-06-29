<?php

namespace App\Http\Controllers;

use App\Models\Sufficiency;
use App\Models\Crop;
use App\Models\SampleUnit;
use App\Models\GrowthStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SufficiencyController extends Controller{
	public function __construct(){
		$this->middleware('auth');
	}

	public function index(){
		// $suffiency = Sufficiency::orderBy('create_dte', 'desc')->paginate(10);

		$sufficiency = DB::table('sufficiency')
			->join('crop', 'crop.id', '=', 'sufficiency.crop_id')
			->join('sample_unit', 'sample_unit.id', '=', 'sufficiency.sample_unit_id')
			->join('growth_stage', 'growth_stage.id', '=', 'sufficiency.growth_stage_id')
			->orderBy('sufficiency.id', 'desc')
			->paginate(10,['sufficiency.id AS id', 'sufficiency.create_dte AS create_dte', 'sufficiency.added_by AS added_by', 'sufficiency.n_percent', 'sufficiency.no3_ppm', 'sufficiency.p_percent',
				'sufficiency.po4_ppm', 'sufficiency.k_percent', 'sufficiency.ca_percent', 'sufficiency.mg_percent', 'sufficiency.s_percent', 'sufficiency.b_ppm', 'sufficiency.cu_ppm', 'sufficiency.fe_ppm',
				'sufficiency.mn_ppm', 'sufficiency.zn_ppm', 'sufficiency.na_percent', 'sufficiency.cl_percent',
				'crop.name AS cropName', 'crop.sub_type', 'crop.image_url as imageUrl',
				'growth_stage.name_desc AS growthStageName', 'sample_unit.name_desc AS sampleUnitName'], 'page', null, 'sufficiency');

		$numRows = Sufficiency::count();

		return view('pages.sufficiency-list', compact('sufficiency', 'numRows'));
	}


	public function fetch_all_suff_page() {
		return view('pages.sufficiency_json');
	}
	/**
	 * @desc for the moment this is just to export the data for the DB.
	 * @params json_out [boolean]
	 * @return false|string || \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function fetch_all_suff() {
		$return_suff = [];
		$sufficiency = DB::table('sufficiency')
		                 ->join('crop', 'crop.id', '=', 'sufficiency.crop_id')
		                 ->join('sample_unit', 'sample_unit.id', '=', 'sufficiency.sample_unit_id')
		                 ->join('growth_stage', 'growth_stage.id', '=', 'sufficiency.growth_stage_id')
						 ->orderBy('sufficiency.id', 'asc')
		                 ->get(['sufficiency.id AS id', 'sufficiency.create_dte AS createDte',
			                 'crop.id AS cropId','crop.name AS cropName', 'crop.sub_type AS cropSubType', 'crop.image_url as imageUrl',
			                 'growth_stage.id AS growthStageId','growth_stage.name_desc AS growthStageName',
			                 'sample_unit.id AS sampleUnitId', 'sample_unit.name_desc AS sampleUnitName','sufficiency.n_percent', 'sufficiency.no3_ppm', 'sufficiency.p_percent',
			                 'sufficiency.po4_ppm', 'sufficiency.k_percent', 'sufficiency.ca_percent', 'sufficiency.mg_percent', 'sufficiency.s_percent', 'sufficiency.b_ppm', 'sufficiency.cu_ppm', 'sufficiency.fe_ppm',
			                 'sufficiency.mn_ppm', 'sufficiency.zn_ppm', 'sufficiency.na_percent', 'sufficiency.cl_percent'])
		                 ->all();

		// one test method of removing the escape character
		// $t = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($sufficiency));
		foreach ($sufficiency as $key=>$value) {
			$value->cropName = ($value->cropSubType === '' || strtolower($value->cropSubType) == 'general' || strtolower($value->cropSubType) == 'none') ? $value->cropName : $value->cropName . ' / ' . $value->cropSubType;
			unset($value->cropSubType);
		}

		return json_encode(['status'=>200, 'message'=>'Your list of Sufficiencies', 'list'=>$sufficiency]);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create(){
		$crops = Crop::all();
		$growthstage = GrowthStage::all();
		$sample_unit = SampleUnit::all();
		return view('pages.sufficiency-create', compact('crops', 'growthstage', 'sample_unit'));
	}

	/**
	 * @desc ADD A NEW SUFFICIENCY TO THE APP AND DB....
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function save(Request $request){
		/// THESE ARE THE CURRENT ELEMENT FIELDS IN THE SUFFICIENCY FORM
		$optional_fields = ['nPercent' => 'n_percent', 'no3PPM'=>'no3_ppm', 'pPercent'=>'p_percent', 'po4PPM'=>'po4_ppm',
		                    'kPercent'=>'k_percent', 'caPercent'=>'ca_percent', 'mgPercent'=>'mg_percent', 'sPercent'=>'s_percent', 'bPPM'=>'b_ppm', 'cuPPM'=>'cu_ppm',
							'fePPM'=>'fe_ppm', 'mnPPM'=>'mn_ppm', 'znPPM'=>'zn_ppm', 'naPercent'=>'na_percent', 'clPercent'=>'cl_percent'];

		if($request->growthStageId === '? undefined:undefined ?'){
			$request->growthStageId = 0;
			$request['growthStageId'] = 0;
			$_POST['growthStageId'] = 0;
		}
		if($request->sampleUnitId === '? undefined:undefined ?'){
			$request->sampleUnitId = 0;
			$request['sampleUnitId'] = 0;
			$_POST['sampleUnitId'] = 0;
		}

		// TODO: NEED TO DO A EITHER OR KIND OF VALIDATION.  IT SHOULD HAVE  A NEW VAL OR THE NEW ONE
		// ALSO IT'S RETURNING AN ERROR OF MAX 3 CHAR WHEN IT'S EMPTY

		$this->validate($request, [
			'cropId' => 'min:1|max:3|required',
			'growthStageId' => 'min:1|max:3',
			'sampleUnitId' => 'min:1|max:3']);

		$suff = $request->all();
		Log::info('this is the variable >>> bracket -> >>> ' . $suff['growthStageId']);

		foreach ($optional_fields as $key=>$val ){
			if (isset($suff[$key]) && $suff[$key] != '') {
				$suff[$val] = $suff[$key];
			}
		}
		// maybe seperate the arrays to make debuggin easier.... $suff from request->all and array to save...
		$suff['crop_id'] = $suff['cropId'];
		$suff['growth_stage_id'] = $suff['growthStageId'];
		$suff['sample_unit_id'] = $suff['sampleUnitId'];
		$suff['added_by'] = Auth::user()->id;
		$suff['last_update_by'] = Auth::user()->id;
		$suff['create_dte'] = time();
		$suff['last_update'] = time();


		// I'm using this method because there are many repeats and want to minimize data scattering
		if(isset($suff['newGrowthStageLabel']) && $suff['growthStageId'] === 0){
			$gsNew = new GrowthStage();
			$gsNew->name_desc = $suff['newGrowthStageLabel'];
			$res = $gsNew->save();
			$suff['growth_stage_id'] = DB::getPdo()->lastInsertId();
		}

		if(isset($suff['newSampleUnitLabel']) && $suff['sampleUnitId'] === 0){
			$suNew = new SampleUnit();
			$suNew->name_desc = $suff['newSampleUnitLabel'];
			$res_su = $suNew->save();
			$suff['sample_unit_id'] = DB::getPdo()->lastInsertId();
		}

		Sufficiency::create($suff);
		\Session::flash('flash_message', 'Successfully added a New Sufficiency!');
		//will need  a proper response here
		//return response()->json($compatibility);
		return redirect('admin/sufficiency/create');
	}

	public function updateForm($id){
		$crops = Crop::all();
		$growthstage = GrowthStage::all();
		$sample_unit = SampleUnit::all();
		$suff = Sufficiency::find($id);
		return view('pages.sufficiency-update', compact('suff','crops', 'growthstage', 'sample_unit'));
	}

	public function update(Request $request, $id){
		$suff = Sufficiency::find($id);

		/// THESE ARE THE CURRENT ELEMENT FIELDS IN THE SUFFICIENCY FORM
		$optional_fields = ['nPercent' => 'n_percent', 'no3PPM'=>'no3_ppm', 'pPercent'=>'p_percent', 'po4PPM'=>'po4_ppm',
		                    'kPercent'=>'k_percent', 'caPercent'=>'ca_percent', 'mgPercent'=>'mg_percent', 'sPercent'=>'s_percent', 'bPPM'=>'b_ppm', 'cuPPM'=>'cu_ppm',
		                    'fePPM'=>'fe_ppm', 'mnPPM'=>'mn_ppm', 'znPPM'=>'zn_ppm', 'naPercent'=>'na_percent', 'clPercent'=>'cl_percent'];

		if($request->growthStageId === '? undefined:undefined ?'){
			$request->growthStageId = 0;
			$request['growthStageId'] = 0;
			$_POST['growthStageId'] = 0;
		}
		if($request->sampleUnitId === '? undefined:undefined ?'){
			$request->sampleUnitId = 0;
			$request['sampleUnitId'] = 0;
			$_POST['sampleUnitId'] = 0;
		}

		// TODO: NEED TO DO A EITHER OR KIND OF VALIDATION.  IT SHOULD HAVE  A NEW VAL OR THE NEW ONE
		// ALSO IT'S RETURNING AN ERROR OF MAX 3 CHAR WHEN IT'S EMPTY

		$this->validate($request, [
			'cropId' => 'min:1|max:3|required',
			'growthStageId' => 'min:1|max:3',
			'sampleUnitId' => 'min:1|max:3']);


		foreach ($optional_fields as $key=>$val ){
			if (isset($request->$key)) {
				$suff->$val = $request->$key;
			}
			else{
				$suff->$val = '';
			}
		}
		// maybe seperate the arrays to make debuggin easier.... $suff from request->all and array to save...
		$suff->crop_id = $request->cropId;
		$suff->growth_stage_id = $request->growthStageId;
		$suff->sample_unit_id = $request->sampleUnitId;
		$suff->last_update = time();
		$suff->last_update_by = Auth::user()->id;


		// I'm using this method because there are many repeats and want to minimize data scattering
		if(isset($suff['newGrowthStageLabel']) && $suff['growthStageId'] === 0){
			$gsNew = new GrowthStage();
			$gsNew->name_desc = $suff['newGrowthStageLabel'];
			$res = $gsNew->save();
			$suff['growth_stage_id'] = DB::getPdo()->lastInsertId();
		}

		if(isset($suff['newSampleUnitLabel']) && $suff['sampleUnitId'] === 0){
			$suNew = new SampleUnit();
			$suNew->name_desc = $suff['newSampleUnitLabel'];
			$res_su = $suNew->save();
			$suff['sample_unit_id'] = DB::getPdo()->lastInsertId();
		}

		$suff->save();
		\Session::flash('flash_message', 'Successfully Updated Sufficiency!');
		//will need  a proper response here
		//return response()->json($compatibility);
		return redirect('admin/sufficiency/list');
	}

	public function delete($id){
		$suff = Sufficiency::find($id);
		$suff->delete();

		\Session::flash('flash_message', 'Successfully removed Sufficiency Row!');
		return redirect('admin/sufficiency/list');
	}
}
