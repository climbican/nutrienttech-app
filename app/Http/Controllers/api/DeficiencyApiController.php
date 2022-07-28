<?php
/**
 * Name: CropApiController
 *
 * Description: This is to be used by the app when accessing the db.
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
 */

namespace App\Http\Controllers\api;

use App\Models\Deficiency;
use App\Models\ImageStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Faker\Provider\Image;

class DeficiencyApiController{

	var $test_mode = false;
	var $image_name = null;
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

	public function __construct() {}

	public function fetch_list_from_crop_id($id): string {
		$query = 'SELECT i.linked_table_id, d.id AS defId, d.name_short AS nameShort, d.element_id AS elementId, i.image_name AS imageName
					FROM  deficiency d
					INNER JOIN image_store i
					    ON d.id = i.linked_table_id
					WHERE i.active = 1
					  AND d.crop_id = :cropId
					AND i.link_table = \'deficiency\';';
		$result = DB::select($query, ['cropId'=>$id]);
		// filter multiple element deficiencies
		$result = $this->super_unique($result, 'elementId');
		// filter multipler images for the same def
		$result = $this->super_unique($result, 'defId');

		return json_encode(['status'=> 200, 'message'=>'Deficiency list', 'defList'=>$result]);
	}

	public function deficiency_detail(int $id) {
		// probably not ever going
		if($this->test_mode)DB::enableQueryLog();

		$result = Deficiency::where('id', '=', $id)->get(['id', 'name_short AS nameShort', 'deficiency_description AS defDescription', 'element_id AS elementId']);

		$images = DB::select('SELECT image_name AS imageName FROM image_store WHERE active = 1 AND linked_table_id = :defId;', ['defId'=>$id]);

		if($this->test_mode){
			print_r($images);
			$query = DB::getQueryLog();
			$query = end($query);
			print_r($query);
			exit();
		}

		$res = $result[0];
		$res['images'] = $images;

		return json_encode(['status'=> 200, 'message'=>'Deficiency detail', 'defDetail'=>$res]);
	}

	public function deficiency_exists(int $crop_id, int $element_id, int $internal=0) {
        $def_exists_query = DB::select('SELECT count(*) AS count FROM deficiency WHERE crop_id = ? AND  element_id = ?;', [$crop_id, $element_id]);
        $res = $def_exists_query[0]->count === 1 ? 1:0;

        if($internal === 0) {
            return json_encode(['status' => 200, 'data' => $res, 'message' => 'Result of def exists.']);
        }
        else {
            return $res;
        }
    }

	/**
	 * @desc add new image which needs approval, THIS FUNCTION ALSO ADDS A NEW DEFICIENCY WHEN THE DATA IS PRESENT
	 * @param Request $request JSON {imageData:base64, deficiencyId:number, ?name:string, ?descriptor:string, ?deficiencyTraits}
	 * @return string - containing status of request
     *
     * [2021-06-08 11:33:47] production.DEBUG: newDeficiency params >>> stdClass Object
    (
    [cropId] => 11
    [elementId] => 4
    [title] => Potas
    [description] => Ium Def desc
    )
     */
	public function add_new_image(Request $request) : string {

		//bounce back info for now
		// try {
			$tmp = json_decode(file_get_contents('php://input'));
        Log::info('info for deficiency :: ' . print_r($tmp, true));
			//echo 'value of  id>>>'.$tmp->deficiencyId;
			// CORS IS NOT PLAYING NICE SO I HAD TO USE PLAIN/TEXT ON THE REQUEST BODY
			// THE RESULT IS THAT LARAVEL DOES NOT RECOGNIZE IT AS JSON... MAKES SENSE.
        Log::info('is the id there? ' . $tmp->deficiencyId);
			$id = $tmp->deficiencyId;// $request->input('deficiencyId');  // this works
			$image = $tmp->imageData; // $request->input('imageData');
            $def_id = $tmp->deficiencyId;;
            $name_short = '';

			// validate that the deficiency exists
            // TODO: check this section, it's not quit working
            if(count((array)$tmp->newDeficiency) < 1) {
                $def = Deficiency::find($id);
                //FIRST LET'S MAKE SURE THE DATA IS VALID OR AS MUCH SO AS POSSIBLE.
                if(!isset($def->name_short) || $def->name_short === '') {
                    return json_encode(['status'=> 400, 'message'=>'There was an issue with the request']);
                }
                $def_id = $def->id;
                $name_short = $def->name_short;
            }
            // check for valid image format start
			if( !substr( $image, 0, 5 ) === "data:" ){
				return json_encode(['status'=> 400, 'message'=>'Invalid data format.']);
			}

            if(!$this->save_base64_image($image, $name_short)) {
                Log::debug('CALL SAVE_BASE64_IMAGE there was an issue saving the image');
                return json_encode(['status'=>400, 'message'=>'There was an issue while saving the uploaded image']);
            }

			// IF THERE IS A NEW DEFICIENCY THAT NEEDS TO BE ADDED FIRST.  SO THAT LINK_TABLE_ID IS CORRECT
            if (count((array)$tmp->newDeficiency) > 1){
                if(!isset($tmp->newDeficiency->cropId) || !isset($tmp->newDeficiency->elementId) || !isset($tmp->newDeficiency->title) || !isset($tmp->newDeficiency->description)){
                    return json_encode(['status'=> 419, 'message'=>'Missing fields.']);
                }
                $def_exists = $this->deficiency_exists($tmp->newDeficiency->cropId, $tmp->newDeficiency->elementId, 1);
                if($def_exists === 0){
                    Log::debug('add new def');
                    $def = new Deficiency();
                    $def->element_id = $tmp->newDeficiency->elementId;
                    $def->crop_id = $tmp->newDeficiency->cropId;
                    $def->name_short = $tmp->newDeficiency->title;
                    $def->deficiency_description = $tmp->newDeficiency->description;
                    $def->crowdsourced = 1;
                    $def->active = 0;
                    $def->added_by = 99;
                    $def->create_dte = time();
                    $def->last_update = time();
                    $def->removed_dte = time();

                    $wtf = $def->save();
                    //Log::debug('wtf res??? >>> ' . print_r($wtf, true));
                    if($wtf){
                        $def_id = $def->id;
                    }
                    else {
                        Log::debug('there was an issue adding the deficiency');
                    }
                }
            }
			// save image to database New Image Store
			$nis = new ImageStore();
			$nis->link_table = 'deficiency';
			$nis->linked_table_id = $def_id;
			$nis->image_name = $this->image_name;
			$nis->active = false;
			$nis->approved_by = 0;
			$nis->create_dte = time();
			$nis->create_by = 999; // current temp code for user generated image
			$nis->last_update = time();
			$nis->last_update_by = 999;

			// save the new image
			$nis->save();
			return json_encode(['status'=>200, 'message'=>'Deficiency image successfully added.']);
		/**}
		catch(\Exception $e) {
		    Log::debug('there was an issue saving the image in the catch section');
			return json_encode(['status'=>419, 'message'=>'There was an issue saving the image']);
		}**/
	}

	/**
	 * @desc CONVERT BASE 64 IMAGE TO JUST IMAGE :)
	 *
	 * NOTE: future feature would be to add thumbnail
	 *
	 * @param string $base64_image_string
	 * @param string $output_file_name_without_extension
	 *
	 * @return bool
	 */
	private function save_base64_image(string $base64_image_string, string $output_file_name_without_extension): bool{
		$splited = explode(',', substr( $base64_image_string , 5 ) , 2);
		$mime=$splited[0];
		$data=$splited[1];
		$mime_split_without_base64=explode(';', $mime,2);
		$mime_split=explode('/', $mime_split_without_base64[0],2);
		if(count($mime_split)==2) {
			$extension=$mime_split[1];
			if($extension=='jpeg')$extension='jpg';
			$img_name = substr(sha1(preg_replace("/[^ \w]+/", '',time().$output_file_name_without_extension ) ), 0, 40);
			$this->image_name = $img_name.'.'.$extension;
		}
		else{
            Log::debug('save_base64_image false oh crap');
			return false;
		}
		file_put_contents( public_path('/images/def/'. $this->image_name), base64_decode($data) );
        $this->create_thumb( public_path('/images/def/'.$this->image_name), public_path('/images/def/thumb/'.$this->image_name), 400);

		return true;
	}

	function super_unique($array,$key){
		$temp_array = [];
		foreach ($array as &$v) {
			if (!isset($temp_array[$v->$key]))
				$temp_array[$v->$key] =& $v;
		}
		$array = array_values($temp_array);
		return $array;

	}



	/// //// todo: this needs to be moved to an image management class
    ///
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
        $type = $this->exif_imagetype('file://'.$src);Log::debug('type >> ' . print_r($type, true));
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




}
