<?php

namespace App\Http\Controllers;

use App\Models\Compatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompatibilityController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $compatibility = Compatibility::orderBy('create_dte', 'desc')->paginate(10);

        $numRows = Compatibility::count();

        return view('pages.compat-list', compact('compatibility', 'numRows'));
        //return response()->json($blogs);
    }

    public function fetchList(){
        $result = [];
        $items = Compatibility::all();

        $numRows = Compatibility::count();

        foreach($items as $item) {
            array_push($result, array('id'=>$item->id, 'name'=>$item->name_short, 'compText'=>$item->comp_text,
                'createDte'=>$item->create_dte, 'lastUpdate'=>$item->last_update));
        }

        $comp = ['compat'=>$result, 'numRows'=>$numRows];

        return json_encode($comp);
    }
    //CREATE NEW COMPATIBILITY
    public function create(){
        return view('pages.compat-create');
    }
    //ADD NEW COMPATIBILITY TO DATABASE
    public function save(Request $request){
        $this->validate($request,[
            'nameShort'=>'min:3|max:25',
            'compText'=>'max:3000|required']);

        $compatibility = $request->all();

        $compatibility['name_short'] = '';
        $compatibility['comp_text'] = '';

        if(isset($compatibility['nameShort']) && $compatibility['nameShort'] != ''){ $compatibility['name_short'] = $compatibility['nameShort']; }
        if(isset($compatibility['compText']) && $compatibility['compText'] != ''){ $compatibility['comp_text'] = $compatibility['compText']; }
        $compatibility['added_by'] = Auth::user()->id;
        $compatibility['create_dte'] = time();
        $compatibility['last_update'] = time();

        Compatibility::create($compatibility);
        \Session::flash('flash_message', 'Successfully created an Compatibility check!');
        //will need  a proper response here
        //return response()->json($compatibility);
        return redirect('admin/compatibility/create');
    }

    public function updateForm($id){
        $compatibility = Compatibility::find($id);

        return view('pages.compat-update', compact('compatibility'));
    }

    public function update(Request $request, $id){
        $compatibility = Compatibility::find($id);

        $this->validate($request,[
            'compText'=>'max:3000|required']);

        $validated = $request->all();
        //$inventory->bar_code = $request->input('bar_code');

        if($request->input('nameShort') != ''){ $compatibility->name_short = $request->input('nameShort'); }
        if($request->input('compText') != ''){ $compatibility->comp_text = $request->input('compText'); }
        $compatibility->last_update_by = Auth::user()->id;
        $compatibility->last_update = time();

        $compatibility->save();
        \Session::flash('flash_message', 'Successfully updated Compatibility!');

        ///session just adds a variable that can be detected in form /// add conditional notice on complete

        return redirect('admin/compatibility/list');
    }

    public function delete($id){
        $compatibility = Compatibility::find($id);
        $compatibility->delete();
        return redirect('admin/compatibility/list');
    }
}
