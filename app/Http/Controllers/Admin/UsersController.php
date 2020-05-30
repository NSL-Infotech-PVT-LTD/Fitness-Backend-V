<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Auth;
use HasRoles;
use DataTables;

class UsersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $__rulesforindex = ['first_name' => 'required', 'email' => 'required'];

    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $users = User::where('id', '!=', Auth::id())->where('first_name', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $users = User::where('id', '!=', Auth::id())->latest()->paginate($perPage);
        }

        return view('admin.users.index', compact('users'));
    }

    public function indexByRoleId(Request $request, $role_id) {
//        $keyword = $request->get('search');
//        $perPage = 5;
//
//        $roleusers = \DB::table('role_user')->where('role_id', $role_id)->pluck('user_id');
//        if (!empty($keyword)) {
//            $users = User::wherein('id', $roleusers)->where('firstname', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")->latest()->paginate($perPage);
//        } else {
//            $users = User::wherein('id', $roleusers)->latest()->paginate($perPage);
//        }
////        dd($role_id);
//        return view('admin.users.index', compact('users', 'role_id'));


        if ($request->ajax()) {
            $roleusers = \DB::table('role_user')->where('role_id', $role_id)->pluck('user_id');
            $users = User::wherein('id', $roleusers)->latest();
            return Datatables::of($users)
                            ->addIndexColumn()
                            ->addColumn('action', function($item)use($role_id) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= " <a href=" . url('/admin/users/' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                         <a href=" . url('/admin/users/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>
                                          <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/users/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";

                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.users.index', ['rules' => array_keys($this->__rulesforindex), 'role_id' => $role_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request) {
        $this->validate(
                $request, [
            'first_name' => 'required',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required',
            'roles' => 'required'
                ]
        );
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        return redirect(url()->previous())->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id) {
        $user = User::findOrFail($id);
        if ($user->hasRole('Super-Admin')):
            return view('admin.users.show.superadmin', compact('user'));
        elseif ($user->hasRole('Personal-Trainer')):
            return view('admin.users.show.trainer', compact('user'));
        else:
            return view('admin.users.show.customer', compact('user'));
        endif;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id) {

        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        $user = User::with('roles')->select('id','first_name','middle_name','last_name','child','mobile','emergency_contact_no','email','password','birth_date','marital_status','designation','emirates_id','address','status')->findOrFail($id);
        $user_roles = [];
        foreach ($user->roles as $role) {
            $user_roles[] = $role->name;
        }

        return view('admin.users.edit', compact('user', 'roles', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int      $id
     *
     * @return void
     */
    public function update(Request $request, $id) {

        $this->validate(
                $request, [
            'first_name' => 'required',
            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
//                    'roles' => 'required'
                ]
        );

        $data = $request->except('password');
//       dd($data);
        if ($request->has('password')) {
            if (!empty($request->password))
                $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($id);
//       dd($user);
        $user->update($data);

//        $user->roles()->detach();
//        foreach ($request->roles as $role) {
//            $user->assignRole('Customer');
//            $user->assignRole($role);
//        }
//dd('aa');
        return redirect(url()->previous())->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id) {
        if (User::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $user = User::findOrFail($request->id);
        $user->status = $request->status == 'Block' ? '0' : '1';
        $user->save();
        return response()->json(["success" => true, 'message' => 'User updated!']);
    }

}
