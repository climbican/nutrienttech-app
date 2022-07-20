<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index() {
        $profiles = User::orderBy('create_dte', 'desc')->paginate(10);
        $numRows = User::count();

        return view('pages.user-list', compact('profiles', 'numRows'));
    }

    public function fetchUser($id) {
        $profile = User::find($id);

        $pr = array('name'=>$profile->name, 'email'=>$profile->email, 'userType'=>$profile->user_type);

        $p = ['profile'=>$pr];

        return json_encode($p);
    }

    public function create() {
        return view('pages.user-create');
    }

    public function updateForm($id) {
        $user = User::find($id);

        return view('pages.user-update', compact('user'));
    }

    public function save(Request $request) {

        $u = array();
        $current_time = time();
        $this->validate($request, [
            'email'=>'required|min:10|max:75|email',
            'userName'=>'required|min:3|max:35',
            'password'=>'required|min:6',
            'confirmPassword'=>'required|min:6|same:password']);

        $user = $request->all();

        $u['user_type'] = 'user';
        (isset($request->userType) && $request->userType !== '') ? '' : '';

        $u['name'] = $request->userName;
        $u['email'] = $request->email;
        $u['user_type'] = $request->userType;
        $u['password'] = bcrypt( $request->input('password') );
        $u['create_dte'] = $current_time;
        $u['last_update'] = $current_time;

        User::create($u);

        return redirect('admin/profile/list')->with('success',  'Successfully added '.$u['name'].'!');
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        $this->validate($request, [
            'oldPassword'=>'required|min:6',
            'newPassword'=>'required|min:6',
            'confirmPassword'=>'required|min:6|same:newPassword']);


        if( !strcmp(bcrypt($request->input('oldPassword')), $user->password)){
            Session::flash('flash_message', 'passwords did Not match');
            return redirect('admin/profile/update/'.$user->id);
        }

        $user->password = bcrypt( $request->input('newPassword') );

        $user->save();
        Session::flash('flash_message', 'Successfully updated profile for '.$user->name);
        return redirect('/');
    }

    public function delete($id): RedirectResponse {
        $user = User::find($id);
        $user->delete();
        Session::flash('flash_message', 'User '.$user->name. ' was successfully removed');
        return redirect('admin/profile/list');
    }
}
