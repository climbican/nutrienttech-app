<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\File;
use Illuminate\View\View;

class CropController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index($asJson=false) {
        $crops = Crop::orderBy('create_dte', 'desc')->paginate(10);
        $numRows = Crop::count();

        foreach ($crops as $crop) {
            if(!file_exists(public_path('images/crop/'.$crop->image_url)) || $crop->image_url === ''){
                $crop->image_url = 'no-image.png';
            }
            $result[] = ['id' => $crop->id, 'cropName' => $crop->name, 'subType' => $crop->sub_type, 'imageUrl' => $crop->image_url,
                'createDte' => $crop->create_dte, 'lastUpdate' => $crop->last_update];
        }
        if(!$asJson){
            return view('pages.crop-list', compact('crops', 'numRows'));
        }
        else{
            return json_encode($crops, 200);
        }

    }
    /**
     * @Desc: FETCH A LIST OF THE ELEMENTS LISTED IN THE DB
     *
     * @return string
     */
    public function fetchList(){
        $result = [];
        $items = Crop::orderBy('name')->get();

        foreach($items as $item) {
            array_push($result, array('id'=>$item->id, 'name'=>$item->name.' '.$item->sub_type, 'image'=>$item->image_url)) ; //'symbol'=>$item->symbol,  future use
        }

        return json_encode($result);
    }
    //CREATE NEW COMPATIBILITY
    public function create(){
        return view('pages.crop-create');
    }

    //ADD NEW COMPATIBILITY TO DATABASE
    public function save(Request $request){
        $this->validate($request, [
            'cropName' => 'min:3|max:25|required',
            'subType' => 'min:1|max:25',
            'imageUrl' => 'min:10|max:254']);

        $crop = $request->all();

        $crop['name'] = '';
        $crop['sub_type'] = '';
        $crop['image_url'] = '';

        if (isset($crop['cropName']) && $crop['cropName'] != '') {
            $crop['name'] = $crop['cropName'];
        }
        if (isset($crop['subType']) && $crop['subType'] != '') {
            $crop['sub_type'] = $crop['subType'];
        }

        if($request->hasFile('cropImage')){
            $imageTmpName = explode('.', $request->file('cropImage')->getClientOriginalName());
            $imageName = sha1(preg_replace("/[^ \w]+/", '',time().$imageTmpName[0] ) );
            //Image::make($image->getRealPath())->resize(200, 200)->save($path);

            $crop['image_url'] = substr($imageName, 0,40).'.'.$imageTmpName[1];
            $request->file('cropImage')->move(base_path() . '/public/images/crop/', $crop['image_url']);
        }
        $crop['added_by'] = Auth::user()->id;
        $crop['last_update_by'] = Auth::user()->id;
        $crop['create_dte'] = time();
        $crop['last_update'] = time();

        Crop::create($crop);
        Session::flash('flash_message', 'Successfully a New Crop!');
        //will need  a proper response here
        //return response()->json($compatibility);
        return redirect('admin/crop/create');
    }

    public function updateForm($id){
        $crop = Crop::find($id);

        if(!file_exists(public_path('images/crop/'.$crop->image_url)) || $crop->image_url === ''){
            $crop->image_url = 'no-image.png';
        }

        return view('pages.crop-update', compact('crop'));
    }

    public function update(Request $request, $id){
        $crop = Crop::find($id);

        $this->validate($request, [
            'cropName' => 'min:3|max:25|required',
            'subType' => 'min:1|max:25',
            'imageUrl' => 'min:10|max:254']);

        $validated = $request->all();

        $crop['name'] = '';
        $crop['sub_type'] = '';
        $crop['image_url'] = $crop->image_url;

        if (isset($validated['cropName']) && $validated['cropName'] != '') {
            $crop['name'] = $validated['cropName'];
        }
        if (isset($validated['subType']) && $validated['subType'] != '') {
            $crop['sub_type'] = $validated['subType'];
        }

        if($request->hasFile('cropImage')){
            //remove the old file first
            File::delete(base_path().'/public/images/crop/'.$crop->image_url);
            //add new image
            $imageTmpName = explode('.', $request->file('cropImage')->getClientOriginalName());
            $imageName = sha1(preg_replace("/[^ \w]+/", '',time().$imageTmpName[0] ) );
            //Image::make($image->getRealPath())->resize(200, 200)->save($path);

            $crop['image_url'] = substr($imageName, 0,40).'.'.$imageTmpName[1];
            $request->file('cropImage')->move(base_path() . '/public/images/crop/', $crop['image_url']);
        }

        $crop->last_update_by = Auth::user()->id;
        $crop->last_update = time();

        $crop->save();
        Session::flash('flash_message', 'Successfully updated ' . $crop->name . '!');

        return redirect('admin/crop/list');
    }

    public function delete($id){
        $crop = Crop::find($id);
        if($crop->image_url != ''){
            if(file_exists(public_path('images/crop/'.$crop->image_url)) ){
                File::delete(base_path().'/public/images/crop/'.$crop->image_url);
            }
        }

        $crop->delete();
        Session::flash('flash_message', $crop->name . ' was successfully removed');
        return redirect('admin/crop/list');
    }
}
