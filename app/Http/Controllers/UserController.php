<?php
/**
 * use the counter to get users membership version
 *
 *  set @rank=0;
    select @rank:=@rank+1 AS row_num, id, name, price, duration, `desc`, active
    from pos_products
    order by name;
 */

namespace App\Http\Controllers;

use App\Models\MediaTankModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use App\Models\EntModel;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Helper\Media;
use function PHPUnit\Framework\fileExists;

class UserController extends Controller {

    use Media;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        /**$data = User::orderBy('id','DESC')
            ->join('model_has_roles', 'id', '=', 'model_has_roles.model_id')
            ->where('model_has_roles.role_id', '<>', 8)
            ->paginate(10);**/

        $query = "SELECT u.id, u.name, u.email, m.model_id, m.role_id, r.id AS 'roleId', r.name AS 'roleName'
                    FROM users u
                    INNER JOIN model_has_roles m
                        ON m.model_id = u.id
                    INNER JOIN roles r
                        ON r.id = m.role_id
                    WHERE m.role_id <> 8;";

        $pre = DB::select($query);
        $data = [];

        foreach ($pre as $d) {
            array_push($data, [$d->id, $d->name, $d->email, $d->roleName]);
        }

       $data = json_encode($data);

        return view('users.index',compact('data', $pre));
            //->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::get(['name','id']);

        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse {
        // TODO: REMOVE THE DATE_DEFAULT AND ADD PROFILE ASSOCIATION
        // // need to put this in a configuration

        $timestamp = time();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        //CREATE ENT
        $ent = new EntModel();

        $ent->user_id = $user->id;
        $ent->type_id = 3; // TODO: temp until I remove type id and need to check if its being used since I have it in roles!! !!IMPORTANT
        $ent->active = 1;
        //$ent->addr = $input['addr'];
        //$ent->city_province = $input['city'] . ',' . $input['province'];
        //$ent->zip = $input['zip'];
        $ent->country_code = 'PH'; // temporary until global system is implemented
        $ent->create_dte = $timestamp;
        $ent->last_update = $timestamp;
        $ent->phone = $input['phone'];
        $ent->save();

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * @desc Show the form for editing the specified resource.
     * @param int $id
     * @return View
     */
    public function edit(int $id): View {
        /**
         * u.id AS "id", u.name, u.email, ud.age, ud.dob, ud.gender, ud.marital_status, ud.membership_level, ud.payment_profile, e.avatar, e.active, e.phone,
        e.addr, e.addr_2, e.signed_tos, e.signed_privacy_policy, e.city_province, e.zip
         */

        $user = DB::table('users')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('ent', 'ent.user_id', '=', 'users.id')
            ->where('users.id', '=', $id)
            ->first(['users.id AS id', 'users.name', 'users.email', 'users.created_at', 'ent.active', 'ent.phone', 'ent.avatar', 'model_has_roles.role_id',
                'ent.addr', 'ent.addr_2', 'ent.state_code', 'ent.city_province', 'ent.country_code', 'ent.zip' ]);

        if($user->avatar == '' || $user->avatar == 0) {
            $user->avatar = url('assets/images/logo-sm.png');
        }
        else {
            $user->avatar = url('storage/user/avatar/'. $user->avatar);
        }
        $roles = Role::get(['id', 'name']);
        $user = (object) $user;

        return view('users.edit',compact('user','roles','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id): RedirectResponse {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    public function change_user_password(Request $request, int $id): RedirectResponse {
        $this->validate($request, [
            'password' => 'required'
        ]);

        $u = User::find($id);

        $u->password = Hash::make($request->password);

        $u->save();

        return redirect()->route('users.show', [$id])
                        ->with('success', 'Users password was successfully reset');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse {
        User::find($id)->delete();

        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    /**
     * @param Request $request
     * @param $public_uuid
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector|int
     */
    public function upload_profile_image(Request $request, $public_uuid): RedirectResponse {

        if (!$request->hasFile('profileImage')) {
            return 401;
        }
        else {
            // need to remove old photo
            // add this firstfileExists()
            $file = $request->file('profileImage');
            $fileData = $this->uploads($file, 'user/avatar/');

            $t = explode('/', $request->avatarName);
            $len = count($t);

            if('logo-sm.png' != $t[$len-1]) {
                $this->remove_image( $t[$len-1], 'app/public/user/avatar/');
            }

            // ADD FILE NAME TO ENT MODEL FOR USER
            DB::table('ent')
                ->where('user_id', $public_uuid)  // find your user by their user_id
                ->limit(1)  //  ensure only one record is updated.
                ->update(array('avatar' =>  $fileData['fileName']));  // update the record in the DB.

            /**$media = MediaTankModel::create([
                'media_name' => $fileData['fileName'],
                'media_type' => $fileData['fileType'],
                'media_path' => $fileData['filePath'],
                'media_size' => $fileData['fileSize']
            ]);**/

            // create returnURL based on role id
            $r = DB::select('SELECT role_id FROM model_has_roles WHERE model_id = ?', [$public_uuid]);
            $role_id = $r[0]->role_id;

            try {
                $return_url = match ($role_id) {
                    8 => '/members/' . $public_uuid,
                    default => '/users/' . $public_uuid . '/edit',
                };
            } catch (\UnhandledMatchError $e) {
                // need to log error later
            }
        }

        return redirect($return_url )->with('success', 'Successfully uploaded!');
    }
}
