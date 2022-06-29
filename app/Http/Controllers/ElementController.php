<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElementController extends Controller{
    public function __construct(){
        //$this->middleware('auth');
    }

    public function index(){
        $elements = Element::orderBy('create_dte', 'desc')->paginate(10);
        $numRows = Element::count();

        return view('pages.elements-list', compact('elements', 'numRows'));
    }

    /**
     * @Desc: FETCH A LIST OF THE ELEMENTS LISTED IN THE DB
     *
     * @return string
     */
    public function fetchList(){
        $fields = [];
        $items = Element::orderBy('element_name')->get();

        foreach($items as $item) {
            array_push($fields, array('id'=>$item->id, 'name'=>$item->element_name, 'chemName'=>$item->chemical_name, 'deficiencyText'=>$item->deficiency, 'benefitsText'=>$item->benefits, 'showFlag'=>$item->show_flag)) ; //'symbol'=>$item->symbol,  future use
        }

	    //$fields = stripslashes(json_encode($fields, JSON_NUMERIC_CHECK));
	    //$fields = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$fields);

        //return view('pages.elements_json', compact('fields'));
	    return json_encode($fields);
    }

    //CREATE NEW COMPATIBILITY
    public function create(){
        return view('pages.elements-create');
    }
    //ADD NEW COMPATIBILITY TO DATABASE
    public function save(Request $request){
    	$params_to_save = [];
        $this->validate($request,[
            'symbol'=>'min:3|max:50',
            'elementName'=>'min:1|max:45|required',
            'chemicalName'=>'min:1|max:45',
            'elementDesc'=>'min:10|max:125',
            'deficiencyText'=>'max:250',
            'benefitsText'=>'max:250']);

        $element = $request->all();

        $element['element_name'] = '';
        $element['element_desc'] = '';

        if(isset($element['elementName']) && $element['elementName'] != ''){ $params_to_save['element_name'] = $element['elementName']; }
        if(isset($element['chemicalName']) && $element['chemicalName'] != ''){ $params_to_save['chemical_name'] = $element['chemicalName']; }
        if(isset($element['elementDesc']) && $element['elementDesc'] != ''){ $params_to_save['element_desc'] = $element['elementDesc']; }
        if(isset($element['benefitsText']) && $element['benefitsText'] != ''){$params_to_save['benefits'] = $element['benefitsText'];}
        if(isset($element['deficiencyText']) && $element['deficiencyText'] != ''){$params_to_save['deficiency'] = $element['deficiencyText'];}
        // param to show in app window
        if(isset($element['isVisible']) && $element['isVisible'] !== ''){ $params_to_save['show_flag'] = $element['isVisible']; }
        else{$params_to_save['show_flag'] = 0;}
        // version 3.x
	    //if(isset($element['nutrientGroup']) && $element['nutrientGroup'] !== ''){ $params_to_save['nutrient_group'] = $element['nutrientGroup']; }
	    //if(isset($element['atomicNumber']) && $element['atomicNumber'] !== ''){ $params_to_save['atomic_number'] = $element['atomicNumber']; }

        $params_to_save['added_by'] = Auth::user()->id;
        $params_to_save['create_dte'] = time();
        $params_to_save['last_update'] = time();
        $params_to_save['last_update_by'] = Auth::user()->id;

        Element::create($params_to_save);
        \Session::flash('flash_message', 'Successfully created an Element!');
        //will need  a proper response here
        //return response()->json($element);
        return redirect('admin/element/create');
    }

    public function updateForm($id){
        $element = Element::find($id);

        return view('pages.elements-update', compact('element'));
    }

    public function update(Request $request, $id){
        $element = Element::find($id);
        $this->validate($request,[
            'symbol'=>'min:3|max:50',
            'elementName'=>'min:1|max:45|required',
            'chemicalName'=>'min:1|max:45',
            'elementDesc'=>'min:10|max:75']);

        $validated = $request->all();

        if(isset($validated['elementName']) && $validated['elementName'] != ''){ $element->element_name = $validated['elementName']; }
        if(isset($validated['chemicalName']) && $validated['chemicalName'] != ''){ $element->chemical_name = $validated['chemicalName']; }
        if(isset($validated['elementDesc']) && $validated['elementDesc'] != ''){ $element->element_desc = $validated['elementDesc']; }
        if(isset($validated['benefitsText']) && $validated['benefitsText'] != ''){$element['benefits'] = $validated['benefitsText'];}
        if(isset($validated['deficiencyText']) && $validated['deficiencyText'] != ''){$element['deficiency'] = $validated['deficiencyText'];}
	    if(isset($element['isVisible']) && $element['isVisible'] !== ''){ $element['show_flag'] = $validated['isVisible']; }
	    else{$element['show_flag'] = 0;}

	    $element->last_update_by = Auth::user()->id;
        $element->last_update = time();
        $element->save();
        \Session::flash('flash_message', 'Successfully updated this Element!');

        return redirect('admin/element/list');
    }

    public function delete($id){
        $element = Element::find($id);
        $element->delete();

        \Session::flash('flash_message', 'Successfully removed Element!');
        return redirect('admin/element/list');
    }
}
