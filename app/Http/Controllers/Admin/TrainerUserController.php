<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use App\Role;
use App\TrainerUser;
use Illuminate\Http\Request;
use Auth;
use HasRoles;
use DataTables;

class TrainerUserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $__rulesforindex = ['first_name' => 'required', 'last_name' => 'required', 'email' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $models = TrainerUser::latest();
            return Datatables::of($models)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= " <a href=" . url('/admin/trainer-user/' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                         <a href=" . url('/admin/trainer-user/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>
                                          <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/users/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";

                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.trainer-user.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        return view('admin.trainer-user.create');
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
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required',
            'mobile' => 'required|numeric',
            'birth_date' => 'required|date_format:Y-m-d|before:today',
            'emergency_contact_no' => 'required|numeric',
            'emirates_id' => 'required|regex:/^[a-zA-Z0-9]+$/u|',
                ]
        );
        $data = $request->all();
//        dd($data);
        $data['password'] = bcrypt($data['password']);
        if (isset($data['services']))
            $data['services'] = json_encode($data['services']);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/trainer-user/', $imageName);
            $data['image'] = $imageName;
        }
//        dd($request->role_id);
        TrainerUser::create($data);
        return redirect('admin/trainer-user/')->with('flash_message', 'Trainer Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id) {
        $traineruser = TrainerUser::findOrFail($id);
        return view('admin.trainer-user.show', compact('traineruser'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id) {
        $traineruser = TrainerUser::findOrFail($id);
        return view('admin.trainer-user.edit', compact('traineruser'));
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
            'emirates_id' => 'required|regex:/^[a-zA-Z0-9]+$/u|',
            'image' => 'required',
                ]
        );
        $data = $request->except('password');
        if ($request->has('password')) {
            if (!empty($request->password))
                $data['password'] = bcrypt($request->password);
        }
        if (isset($data['services']))
            $data['services'] = json_encode($data['services']);
        $traineruser = TrainerUser::findOrFail($id);
        $traineruser->update($data);
        return redirect('admin/trainer-user/')->with('flash_message', 'Trainer updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id) {
        if (TrainerUser::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $traineruser = TrainerUser::findOrFail($request->id);
        $traineruser->status = $request->status == 'Block' ? '0' : '1';
        $traineruser->save();
        return response()->json(["success" => true, 'message' => 'Trainer updated!']);
    }

    private static function mailSend($data, $request) {
        \Mail::send('emails.send_paymentlink', $data, function($message) use ($request) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->sender(env('MAIL_FROM_ADDRESS'));
            $message->to($request->email);
            $message->subject('Please subscribe for payment !');
        });
        return true;
    }

}
