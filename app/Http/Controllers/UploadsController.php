<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function uploadImage(Request $request) {
        // getting all of the post data
        $file = array('image' => $request->file('image'));
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = $request->validate($file, $rules);
        if (!$validator) {
            // send back to the page with the input data and errors
            return Redirect('upload')->withInput()->withErrors($validator);
        }
        else {
            // checking file is valid.
            if ($request->file('image')->isValid()) {
                $destinationPath = 'uploads'; // upload path
                $extension = $request->file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image
                $request->file('image')->move($destinationPath, $fileName); // uploading file to given path
                // sending back with message

                return redirect('upload')->with('success', 'Upload successfully');
            }
            else {
                // sending back with error message.
                return redirect('upload')->with('error', 'uploaded file is not valid');
            }
        }
    }
}
