<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use DataTables;
use DB;

class RolesController extends Controller {

    protected $__rulesforindex = ['name' => 'required', 'category' => 'required'];

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $model = Role::all();
            return Datatables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';
                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
                                $return .= "<a href=" . url('/admin/roles/' . $item->id) . " title='View Events'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a><a href=" . url('/admin/roles/' . $item->id . '/edit') . " title='Edit Events'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/roles/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.roles.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        $permissions = Permission::select('id', 'name', 'label')->get()->pluck('label', 'name');

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request) {
        $this->validate($request, ['name' => 'required', 'category' => 'required']);
        $requestData = $request->all();

//        dd($requestData);
        $role = Role::create($requestData);
        $role->permissions()->detach();
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission_name) {
                $permission = Permission::whereName($permission_name)->first();
                $role->givePermissionTo($permission);
            }
        }

        foreach (['monthly', 'quarterly', 'half_yearly', 'yearly'] as $feetype):
            if ($request->$feetype == null || $request->$feetype == '')
                continue;
            $rolePlans = ['role_id' => $role->id, 'fee_type' => $feetype, 'fee' => $request->$feetype];
            if ($request->hasfile($feetype . '_image')) {
                $imageName = uniqid() . '.' . $request->file($feetype . '_image')->getClientOriginalExtension();
                $request->file($feetype . '_image')->move(base_path() . '/public/uploads/roles/', $imageName);
                $rolePlans += ['image' => $imageName];
            }
            \App\RolePlans::create($rolePlans);
        endforeach;
        return redirect('admin/roles')->with('flash_message', 'Role added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id) {
        $role = Role::findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::select('id', 'name', 'label')->get()->pluck('label', 'name');
//        dd($role->toArray());
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return void
     */
    public function update(Request $request, $id) {
        $rules = ['name' => 'required', 'category' => 'required'];
//        if ($request->has('image'))
//            $rules += ['image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450'];
        $this->validate($request, $rules);
        $requestData = $request->all();
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/roles/', $imageName);
            $requestData['image'] = $imageName;
        }
        $role = Role::findOrFail($id);
        $role->update($request->all());
        $role->permissions()->detach();
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission_name) {
                $permission = Permission::whereName($permission_name)->first();
                $role->givePermissionTo($permission);
            }
        }
//        dd($request->all());
        foreach (['monthly', 'quarterly', 'half_yearly', 'yearly'] as $feetype):
            if ($request->$feetype == null || $request->$feetype == '')
                continue;
            $rolePlan = \App\RolePlans::where('role_id', $id)->where('fee_type', $feetype)->get();
//            dd($rolePlan->isEmpty());
            $rolePlans = ['role_id' => $id, 'fee_type' => $feetype, 'fee' => $request->$feetype];
            if ($request->hasfile($feetype . '_image')) {
                $imageName = uniqid() . '.' . $request->file($feetype . '_image')->getClientOriginalExtension();
                $request->file($feetype . '_image')->move(base_path() . '/public/uploads/roles/', $imageName);
                $rolePlans += ['image' => $imageName];
            }
            if ($rolePlan->isEmpty() == true):
                \App\RolePlans::create($rolePlans);
            else:
                $rolePlanUpdate = \App\RolePlans::findorfail($rolePlan->first()->id);
                $rolePlanUpdate->update($rolePlans);
            endif;
        endforeach;
        return redirect('admin/roles')->with('flash_message', 'Role updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id) {
        Role::destroy($id);

        return redirect('admin/roles')->with('flash_message', 'Role deleted!');
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $event = Role::findOrFail($request->id);
        $event->status = $request->status == 'Block' ? '0' : '1';
        $event->save();
        return response()->json(["success" => true, 'message' => 'Event updated!']);
    }

}
