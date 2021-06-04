<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Bulk;
use App\TrainerUser;
use Illuminate\Http\Request;
use Auth;
use HasRoles;
use DataTables;
use App\Exports\BulkExport;
use App\Imports\BulkImport;
use Maatwebsite\Excel\Facades\Excel;

class TrainerUserController extends Controller {

    public function importExportView() {
	$rows = Excel::toArray(new Bulk, 'Members Data working.xlsx');
//	dd($rows[0]);
	$rowKey = [];
//	$dataId
	$notInstered = [];
	foreach ($rows[0] as $k => $rowValue):
	    if ($k === 0):
		$rowKey = $rowValue;
		continue;
	    endif;
	    $row = array_combine($rowKey, $rowValue);
	    try {
//	    dd($rowValue);
//	    dd($row);
		$role = \App\Role::where('name', 'like', $row['role_name'])->where('category', strtolower($row['role_category']));
		if ($role->count() == 0)
		    continue;
		$roleId = $role->value('id');
		$rolePlan = \App\RolePlans::where('role_id', $roleId)->where('fee_type', $row['role_plan_fee_type']);
		if ($rolePlan->count() == 0)
		    continue;
		$rolePlanId = $rolePlan->value('id');
//	    dd($row);
//             $date=date_create("2013-03-15");
//	    $date=date_create($row['birth_date']);
//	    $myDateTime = DateTime::createFromFormat('Y-m-d', $row['birth_date']);
//            $newDate = $myDateTime->format('m/d/Y');
//	    $newDate = date("Y-m-d", strtotime($row['birth_date']));
//	dd($newDate);
//            $row['birth_date']=$newDate;
		$row['id'] = preg_replace("/[^0-9]/", "", $row['id']);
//	    $row['birth_date']=date('Y-m-d', $row['birth_date']);
//	    dd($row);
		$user = \App\User::create($row);
		$user->assignRole($roleId, 'id');
		\DB::table('role_user')->where('role_id', $roleId)->where('user_id', $user->id)->update(['role_plan_id' => $rolePlanId]);
//	    dd($user);
//	    dd($row, $rowKey, $rowValue, $k);
	    } catch (\Exception $ex) {
		$notInstered[] = $row;
		continue;
	    }
	endforeach;
	dd('done', $notInstered);
    }

    protected $__rulesforindex = ['first_name' => 'required', 'last_name' => 'required', 'email' => 'required'];

    public function index(Request $request) {
//	dd($request->all());
	if ($request->ajax()) {
	    $model= new TrainerUser;
	    if (isset($request->status))
		$model = $model->where('type', $request->status);
	    else
		$model = $model->select('id','first_name','middle_name','last_name','mobile','email');
	    $model = $model->latest();
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

				$return .= " <a href=" . url('/admin/trainer-user/' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                         <a href=" . url('/admin/trainer-user/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>
                                          <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/trainer-user/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";

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
	    'email' => 'required|string|max:255|email|unique:trainer_users',
	    'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/',
	    'mobile' => 'required|numeric',
	    'emergency_contact_no' => 'required|numeric',
	    'emirates_id' => 'required|regex:/^[a-zA-Z0-9]+$/u|',
	    'emirate_image1' => 'required|mimes:jpg,jpeg,png',
	    'emirate_image2' => 'required|mimes:jpg,jpeg,png',
//            'image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450',
	    'birth_date' => 'required|date_format:Y-m-d|before:today',
		]
	);
	$data = $request->all();
	$data['password'] = bcrypt($data['password']);
	if (isset($data['services']))
	    $data['services'] = json_encode($data['services']);
	if ($request->hasfile('image')) {
	    $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
	    $request->file('image')->move(base_path() . '/public/uploads/trainer-user/', $imageName);
	    $data['image'] = $imageName;
	}

	if ($request->hasfile('emirate_image1')) {

	    $imageName1 = uniqid() . '.' . $request->file('emirate_image1')->getClientOriginalExtension();
	    $request->file('emirate_image1')->move(base_path() . '/public/uploads/emirateimages/', $imageName1);
	    $data['emirate_image1'] = $imageName1;
	}
	if (!empty($request->file('emirate_image2')))
	    $data['emirate_image2'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('emirate_image2'), public_path(TrainerUser::$_imagePublicPath), true);
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
//	dd($traineruser);
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
	$rules = [
	    'first_name' => 'required',
	    'email' => 'required|string|max:255|email|unique:trainer_users,email,' . $id,
	    'emirates_id' => 'required|regex:/^[a-zA-Z0-9]+$/u|',
	];
//        if ($request->has('image'))
//            $rules += ['image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450'];
	if ($request->has('password'))
	    if (!empty($request->password))
		$rules += ['password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/'];
	$this->validate(
		$request, $rules
	);
	$data = $request->except('password');
	if ($request->has('password')) {
	    if (!empty($request->password))
		$data['password'] = bcrypt($request->password);
	}
	if (isset($data['services']))
	    $data['services'] = json_encode($data['services']);

	if ($request->hasfile('image')) {
	    $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
	    $request->file('image')->move(base_path() . '/public/uploads/trainer-user/', $imageName);
	    $data['image'] = $imageName;
	}
	if ($request->hasfile('emirate_image1')) {
	    $imageName1 = uniqid() . '.' . $request->file('emirate_image1')->getClientOriginalExtension();
	    $request->file('emirate_image1')->move(base_path() . '/public/uploads/emirateimages/', $imageName1);
	    $data['emirate_image1'] = $imageName1;
	}
	if (!empty($request->file('emirate_image2')))
	    $data['emirate_image2'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('emirate_image2'), public_path(TrainerUser::$_imagePublicPath), true);

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
