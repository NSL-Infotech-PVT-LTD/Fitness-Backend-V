<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\AdminUser as MyModel;
use Illuminate\Http\Request;
use Auth;
use HasRoles;
use DataTables;

class AdminUsersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $__rulesforindex = ['name' => 'required', 'email' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $adminusers = MyModel::latest();
            return Datatables::of($adminusers)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
                                $return = '';
                                $return .= " <a href=" . url('/admin/adminusers/' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                         <a href=" . url('/admin/adminusers/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>
                                          <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/adminusers/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";

                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.adminusers.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**           
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        return view('admin.adminusers.create');
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
            'name' => 'required|alpha',
            'email' => 'required|string|max:255|email|unique:admin_users',
                ]
        );
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        MyModel::create($data);

        return redirect('admin/adminusers')->with('flash_message', 'Admin User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id) {
        $adminuser = MyModel::findOrFail($id);
        return view('admin.adminusers.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id) {
        $adminuser = MyModel::findOrFail($id);

        return view('admin.adminusers.edit', compact('adminuser'));
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
            'name' => 'required',
            'email' => 'required|string|max:255|email|unique:admin_users,email,' . $id,
                ]
        );
        $data = $request->except('password');
        if ($request->has('password')) {
            if (!empty($request->password))
                $data['password'] = bcrypt($request->password);
        }
//dd($data);
        $adminuser = MyModel::findOrFail($id);
        $adminuser->update($data);
        return redirect('admin/adminusers')->with('flash_message', 'Admin User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id) {
        if (MyModel::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $adminuser = MyModel::findOrFail($request->id);
        $adminuser->status = $request->status == 'Block' ? '0' : '1';
        $adminuser->save();
        return response()->json(["success" => true, 'message' => 'User updated!']);
    }

}
