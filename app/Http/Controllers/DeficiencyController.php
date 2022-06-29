<?php
/**
 * Name: DeficiencyController
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
 * Notes:
 *
 * TODO: CHANGE THE THUMB GENERATE IT REALLY SUCKS!!
 */

namespace App\Http\Controllers;
// MODELS
use Faker\Provider\Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Element;
use App\Models\Deficiency;
use App\Models\ImageStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeficiencyController extends Controller{
	// name of the uploaded deficiency image
	private $image_name;
	// Link image type to correct image loader and saver
	// - makes it easier to add additional types later on
	// - makes the function easier to read
	const IMAGE_HANDLERS = [
		IMAGETYPE_JPEG => [
			'load' => 'imagecreatefromjpeg',
			'save' => 'imagejpeg',
			'quality' => 0
		],
		IMAGETYPE_PNG => [
			'load' => 'imagecreatefrompng',
			'save' => 'imagepng',
			'quality' => 0
		],
		IMAGETYPE_GIF => [
			'load' => 'imagecreatefromgif',
			'save' => 'imagegif',
			'quality' => 0
		]
	];

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 *
	 */
	public function index(Request $request, $crowdsourced=false){

	    $crowdsourced_query_part = $crowdsourced == 1 ? ' WHERE d.active = 0 AND d.crowdsourced = 1 ' : ' WHERE d.active = 1 ';
		$query = 'SELECT d.id AS defId, d.name_short, d.deficiency_description, d.added_by, d.create_dte, d.last_update, d.crowdsourced, d.active AS active,
				    COALESCE(image_name, "no-image.png") AS image_name, c.name AS crop_name
					FROM deficiency d
					INNER JOIN crop c
					ON d.crop_id = c.id
					LEFT OUTER JOIN image_store
					    ON d.id = image_store.linked_table_id' .
                    $crowdsourced_query_part.'
					  GROUP BY d.id, image_name
					ORDER BY d.id DESC;';

		$def = DB::select($query);
		$def = $this->super_unique($def, 'defId');
		$numRows = count($def);

		$i=0;
		$def_id = 0;
		// TODO: ---> this is not going to work probably need to do two loops... fart
        // loop and remove all non unique firstt... ? YES crap
        //print_r($def); exit();
		foreach($def as $defe){
			//this "if" just sets image one as the default in the list
            //TODO:  also need to loop through all of the results and set unique.... fart!!!!!
            if ($def_id !== $defe->defId ){
                if(!file_exists(public_path('images/def/'.$defe->image_name)) || $defe->image_name === ''){
                    $def[$i]->image_name = 'no-image.png';
                }
                $i++;
                $def_id = $defe->defId;
            }
            else {
                // remove element that is essentially a duplicate
                array_splice($def, $i);
            }


		}
		$def = $this->arrayPaginator($def, $request);

		return view('pages.deficiency-list', compact('def', 'numRows'));
	}

	function super_unique($array,$key): array {
		$temp_array = [];
		foreach ($array as &$v) {
			if (!isset($temp_array[$v->$key]))
				$temp_array[$v->$key] =& $v;
		}
		$array = array_values($temp_array);
		return $array;

	}
	/**
	 * @return a list of deficiencies, mostly for the mobile app, can serve as a remote fetch later
	 *
	 */
	public function fetchList(): string{
		$def = Deficiency::all();
		$result = [];

		foreach($def as $d){
			// only include descriptions that are over 50 characters long.
			// UPDATE:  --> tested and set live dec 4, 2019 -->
			if(strlen($d->deficiency_description) > 50) {
				array_push($result, array('id'=>$d->id, 'nameShort'=>$d->name_short, 'defDescription'=>$d->deficiency_description, 'elementID'=>$d->element_id,
				                          'cropID'=>$d->crop_id, 'image1'=>$d->image_1, 'image2'=>$d->image_2, 'image3'=>$d->image_3, 'image4'=>$d->image_4));
			}
		}
		return json_encode($result);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create(){
		$crops = DB::table('crop')->orderby('name', 'asc')->orderby('sub_type')->get();
		$elements = Element::all();
		return view('pages.deficiency-create', compact('crops', 'elements'));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 *
	 * IMAGE STORE PARAMS COMING IN
	 *
	 * Array ( [images]   => Array (
	 *                [name] => Array ( [0] => 9a02a266589b210d5ed9197911c02187.jpg [1] => e0483849ce6ce0ed7ee40010fbd465e7.jpg [2] => 2c0e3ef01a712189bcbc61ed29d611b2.jpg )
	 *                [type]     => Array ( [0] => image/jpeg [1] => image/jpeg [2] => image/jpeg )
	 *                [tmp_name] => Array ( [0] => /Applications/MAMP/tmp/php/phpr5liG7 [1] => /Applications/MAMP/tmp/php/phpSHIO3m [2] => /Applications/MAMP/tmp/php/phpbHLOxl )
	 *                [error]    => Array ( [0] => 0 [1] => 0 [2] => 0 )
	 *                [size] => Array ( [0] => 76498 [1] => 23242 [2] => 40007 ) ) )
	 */
	public function save(Request $request, $crowd_sourced=0){
		$nowTime = time();
		$this->validate($request, ['nameShort' => 'max:35|unique:deficiency,name_short|required',
		                           'cropID' => 'required|integer',
		                           'elementID' => 'required|integer']);

		$validated = $request->all();

        $validated['defDescription'] = preg_replace('/<!--block-->/', '',  $validated['defDescription']);

		$d = new Deficiency();

		if ($crowd_sourced == 1) {
            $d->active = 0;
            $d->crowdsourced = 1;
        }
		else {
            $d->active = 1;
            $d->crowdsourced = 0;
        }

		$d->deficiency_description = '';
		$imageReference = $_FILES['images'];
		$num_of_images_sent = count($imageReference['name']);

		if(isset($validated['defDescription']) && $validated['defDescription'] != '' ){ $d->deficiency_description = $validated['defDescription']; }
		$d->name_short = $validated['nameShort'];
		$d->element_id = $validated['elementID'];
		$d->crop_id = $validated['cropID'];
		//TRACKING
		$d->added_by = Auth::user()->id;
		$d->create_dte = $nowTime;
		$d->last_update = $nowTime;
		$d->removed_dte = $nowTime;

		$d->save();

		//NOW GRAB THE ID AND ADD IMAGES TO DB STORE
		for($i=0; $i<$num_of_images_sent; $i++){
			if($imageReference['name'][$i] !== '' && $imageReference['size'][$i] > 100){
				$imageTmpName = explode('.', $imageReference['name'][$i]);
				$imageName = sha1(preg_replace("/[^ \w]+/", '',time().$imageTmpName[0] ) );

				$is = new ImageStore();
				$is->link_table = 'deficiency';
				$is->linked_table_id = $d->id;
				$is->active = 1;
				$is->approved_by = Auth::user()->id;
				$is->image_name = $imageName.'.'.$imageTmpName[1];
				$is->create_by = Auth::user()->id;
				$is->last_update_by = Auth::user()->id;
				$is->create_dte = $nowTime;
				$is->last_update = $nowTime;
				//should be checking for origin size and doing some standarddization... next round...
				$this->create_thumb( $imageReference['tmp_name'][$i], public_path('images/def/thumb/'.$imageName.'.'.$imageTmpName[1]), 200);
				move_uploaded_file( $imageReference['tmp_name'][$i], public_path('images/def/'.$imageName.'.'.$imageTmpName[1]));
				$is->save();
			}
		}

		Session::flash('flash_message', 'Successfully created a new Deficiency Correlation');

		return redirect('admin/deficiency/create');
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * select id, image_name
       from image_store
       where link_table = "deficiency"
       and link_table_id = 348
       and active = 1

     *
	 */
	public function updateForm($show_community_images, $id, $image_id){
		$def = Deficiency::find($id);
		$images = ImageStore::where('linked_table_id', '=', $id)->where('link_table', '=', 'deficiency')->get(['id', 'image_name', 'active', 'create_dte', 'last_update']);

		$crops = DB::table('crop')->orderby('name', 'asc')->orderby('sub_type')->get();
		$elements = Element::all();

		$def->nameShort = $def->name_short;
		$def->defDescription = $def->deficiency_description;
		$def->elementID = $def->element_id;
		$def->cropID = $def->crop_id;
		unset($def->crop_id);

		return view('pages.deficiency-update', compact('def','images', 'crops', 'elements', 'id', 'image_id'));
	}

	/**
	 * @desc REMOVE INDIVIDUAL IMAGE FROM DEFICIENCY
	 * @param $def_id
	 * @param $id
	 *
	 * @return json|false|string
	 *
	 * NOTE: If the app grows more this should be changed to obfuscate the id's and decrypt them here.
	 */
	public function removeImage(int $def_id, int $id): string{
		$remove_item = ImageStore::find($id);
		if($remove_item->link_table === 'deficiency' && $remove_item->linked_table_id === $def_id){
			if(file_exists(public_path('images/def/'.$remove_item->image_name)) ){
				Storage::delete(public_path('/images/def/'.$remove_item->image_name));
				Storage::delete(public_path('/images/def/thumb/'.$remove_item->image_name));
				ImageStore::destroy($remove_item->id);
			}
		}
		else{
			return json_encode(['status'=>400, 'message'=>'There was an issue removing the image']);
		}

		return json_encode(['status'=>200, 'message'=>'You have sucessufully remove the image']);
	}
	/**
	 * @param Request $request
	 * @param $id
	 * @return RedirectResponse
	 */
	public function update(Request $request, $id){
        //return json_encode($request->all());
		$nowTime = time();
		$validated = $request->all();

        $validated['defDescription'] = preg_replace('/<!--block-->/', '',  $validated['defDescription']);

		if(isset($_FILES['images'])){
            $imageReference = $_FILES['images'];
            $num_of_images_sent = count($imageReference['name']);
        }
		else {
		    $num_of_images_sent = 0;
        }

		$this->validate($request, ['nameShort' => 'max:35|required',
		                           'cropID' => 'required',
		                           'elementID' => 'required']);

		$validated['cropID'] = str_replace('number:',"",$validated['cropID']);
		$validated['elementID'] = str_replace('number:', "", $validated['elementID']);

		$d = Deficiency::find($id);
        if (isset($validated['approveCrowdSourcedDeficiency'])) {
            if ((int)$validated['approveCrowdSourcedDeficiency'] === 1) {
                $d->active = 1;
                ImageStore::where('id', '=', $validated['communityAddedImageId'])->update(['active' => 1]);
            }
        }

		$d->name_short = $validated['nameShort'];
		$d->deficiency_description = $validated['defDescription'];
		$d->element_id = $validated['elementID'];
		$d->crop_id = $validated['cropID'];
		//TRACKING
		$d->last_update = $nowTime;
		//$d->removed_dte = $nowTime;

		$d->save();
		// THIS WORKS THE SAME WAY AS ADD, OLD PHOTOS ARE REMOVED BY FRONT END INTERACTION IN ANGULAR FRONT END
		for($i=0; $i<$num_of_images_sent; $i++){
			if($imageReference['name'][$i] !== '' && $imageReference['size'][$i] > 100 && $validated['existing'][$i] == 0){
				$imageTmpName = explode('.', $imageReference['name'][$i]);
				$imageName = sha1(preg_replace("/[^ \w]+/", '',time().$imageTmpName[0] ) );

				$is = new ImageStore();
				$is->link_table = 'deficiency';
				$is->linked_table_id = $d->id;
				$is->active = 1;
				$is->approved_by = Auth::user()->id;
				$is->image_name = $imageName.'.'.$imageTmpName[1];
				$is->create_by = Auth::user()->id;
				$is->last_update_by = Auth::user()->id;
				$is->create_dte = $nowTime;
				$is->last_update = $nowTime;
				//should be checking for origin size and doing some standarddization... next round...
				$this->create_thumb( $imageReference['tmp_name'][$i], public_path('images/def/thumb/'.$imageName.'.'.$imageTmpName[1]), 400);
				move_uploaded_file( $imageReference['tmp_name'][$i], public_path('images/def/'.$imageName.'.'.$imageTmpName[1]));
				$is->save();
			}
		}
		Session::flash('flash_message', 'Successfully Update a new Deficiency Correlation');

		return redirect('admin/deficiency/list/0');
	}

	public function fetchDeficiency($id): string{
		$d = Deficiency::find($id);

		$d->nameShort = $d['name_short'];
		$d->defDescription = $d->deficiency_description;
		$d->elementID = $d->element_id;
		$d->cropID = $d->crop_id;
		unset($d->crop_id);
		unset($d->element_id);
		return json_encode($d);
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 *
	 * NOTE: considered FK delete on cascade but, this will store images associated with multiple tables.
	 *
	 */
	public function delete($id){
		$d = Deficiency::find($id);
		//echo "r >>> ".$id;
		$images = DB::select('SELECT id, image_name FROM image_store WHERE link_table = "deficiency" AND linked_table_id = :link_id;', ['link_id'=>$id]);

		foreach($images as $i){
			if(file_exists(public_path('images/def/'.$i->image_name)) ){
				Storage::delete(public_path('/images/def/'.$i->image_name));
				Storage::delete(public_path('/images/def/thumb/'.$i->image_name));
				ImageStore::destroy($i->id);
			}
		}
		$d->delete();
		return redirect('admin/deficiency/list/0');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 *
	 * Query format
	 * select *
	 *	from image_store i
	 *	inner join deficiency d
	 *	on i.linked_table_id = d.id
	 *	inner join crop c
	 *	on c.id = d.crop_id;
     *
     * NOTE : THIS IS FOR THE COMMUNITY ADDED IMAGES
	 */
	public function deficiencyImagelist(Request $request) {
		$query = 'SELECT i.id, d.id AS defId, i.image_name, d.name_short, d.deficiency_description, c.name AS crop_name, c.sub_type AS sub_type
				  FROM image_store i
				  INNER JOIN deficiency d on i.linked_table_id = d.id
				  INNER JOIN crop c on c.id = d.crop_id
		          WHERE i.link_table = "deficiency"
		          AND i.active = 0;';

		$newImages = DB::select($query);

		$newImages = $this->arrayPaginator($newImages, $request);
		$numRows = count($newImages);

		return view('pages.deficincy-new-images', compact('newImages', 'numRows'));
	}

	/**
	 * @desc this is a TEMPORARY image approval system, it works for now.
	 *
	 * @param number $id
	 *
	 * @throws \Exception
	 */
	public function approveImage($id, $fromImageList){
		$d = ImageStore::find($id);
		$d->active = 1;
		$d->save();
		/**$def = Deficiency::find($d->linked_table_id);
		for($i=1; $i<5; $i++){
			$inc = 'image_'.$i;
			if(is_null($def->$inc) || $def->$inc === '' || $def->$inc === NULL){
				$def->$inc = $d->image_name;
				break;
			}
		}
		$def->save();
		if($i < 5){
			ImageStore::destroy($id);
			Session::flash('flash_message', 'Successfully add the image to position '.$i);
		}
		else{
			Session::flash('flash_message', 'There are already 4 images associated with this deficiency, please remove an existing image.');
		}**/
		if($fromImageList == 1){
            return redirect('admin/deficiency/community_images');
        }
		else {
		    return json_encode(['status'=>200]);
        }
	}

	public function remove_community_image($id){
		$image = ImageStore::find($id);
		//remove image from disk as well
		unlink(public_path('images/def/'.$image->image_name));
		unlink(public_path('images/def/thumb/'.$image->image_name));
		$image->delete();
		Session::flash('flash_message', 'Successfully removed image');
		return redirect('admin/deficiency/community_images');
	}

	/**
	 * @param $src - a valid file location
	 * @param $dest - a valid file target
	 * @param $targetWidth - desired output width
	 * @param $targetHeight - desired output height or null
	 */
	function create_thumb($src, $dest, $targetWidth, $add_extension_name = null, $targetHeight = null) {
		// 1. Load the image from the given $src
		// - see if the file actually exists
		// - check if it's of a valid image type
		// - load the image resource
		// get the type of the image
		// we need the type to determine the correct loader
		$type = $this->exif_imagetype('file://'.$src);
		// if no valid type or no handler found -> exit
		if (!$type || !self::IMAGE_HANDLERS[$type]) {
			return null;
		}
		// load the image with the correct loader
		$image = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $src);
		// no image found at supplied location -> exit
		if (!$image) {
			return null;
		}
		// 2. Create a thumbnail and resize the loaded $image
		// - get the image dimensions
		// - define the output size appropriately
		// - create a thumbnail based on that size
		// - set alpha transparency for GIFs and PNGs
		// - draw the final thumbnail
		// get original image width and height
		$width = imagesx($image);
		$height = imagesy($image);
		// maintain aspect ratio when no height set
		if ($targetHeight == null) {
			// get width to height ratio
			$ratio = $width / $height;
			// if is portrait
			// use ratio to scale height to fit in square
			if ($width > $height) {
				$targetHeight = floor($targetWidth / $ratio);
			}
			// if is landscape
			// use ratio to scale width to fit in square
			else {
				$targetHeight = $targetWidth;
				$targetWidth = floor($targetWidth * $ratio);
			}
		}
		// create duplicate image based on calculated target size
		$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
		// set transparency options for GIFs and PNGs
		if ($type == self::IMAGE_HANDLERS[IMAGETYPE_GIF] || $type == self::IMAGE_HANDLERS[IMAGETYPE_PNG]) {
			// make image transparent
			imagecolortransparent( $thumbnail, imagecolorallocate($thumbnail, 0, 0, 0)
			);
			// additional settings for PNGs
			if ($type == self::IMAGE_HANDLERS[IMAGETYPE_PNG]) {
				imagealphablending($thumbnail, false);
				imagesavealpha($thumbnail, true);
			}
		}
		// copy entire source image to duplicate image and resize
		imagecopyresampled(
			$thumbnail,
			$image,
			0, 0, 0, 0,
			$targetWidth, $targetHeight,
			$width, $height
		);
		// 3. Save the $thumbnail to disk
		// - call the correct save method
		// - set the correct quality level
		// save the duplicate version of the image to disk
		return call_user_func(
			self::IMAGE_HANDLERS[$type]['save'],
			$thumbnail,
			$dest,
			self::IMAGE_HANDLERS[$type]['quality']
		);
	}


	function exif_imagetype ( $filename ) {
		if(!is_file($filename)) return false;
		// TODO: HANDLE FILESIZE 0 !! THIS WILL CRASH THE aop
		if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
			return $type;
		}
		return false;
	}


	public function arrayPaginator($array, $request) {
		$page = $request->input('page', 1);
		$perPage = 10;
		$offset = ($page * $perPage) - $perPage;

		return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
			['path' => $request->url(), 'query' => $request->query()]);
	}
}
