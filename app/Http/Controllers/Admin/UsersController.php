<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Carbon;
use Illuminate\Http\Request;
use Auth;
use HasRoles;
use DataTables;

class UsersController extends Controller {

    protected $__rulesforindex = ['first_name' => 'required', 'last_name' => 'required', 'mobile' => 'required', 'email' => 'required', 'payment_status' => 'required', 'package' => '', 'feature' => '', 'subscription' => 'required', 'payment_date' => '', 'joining_date' => '', 'end_date' => ''];

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

    public function cal_days_in_year($year) {
	$days = 0;
	for ($month = 1; $month <= 12; $month++) {
	    $days = $days + cal_days_in_month(CAL_GREGORIAN, $month, $year);
	}
	return $days;
    }

    public function indexByRoleId(Request $request, $role_id) {
	
	if ($request->ajax()) {
	    $roleusers = \DB::table('role_user')->where('role_id', $role_id)->pluck('user_id')->toArray();
	   
	    if (!empty($request->status)) {
		$users = [];
		if (strtolower($request->status) == "expired" || strtolower($request->status) == "active") {

		    $data = \App\RoleUser::Where('role_id', $role_id)->select('user_id', 'role_plan_id', 'created_at')->get();
		    $plans = \App\RolePlans::Where('role_id', $role_id)->pluck('fee_type', 'id');
//		    dd($plans);
		    $data = collect($data->toArray())->flatten()->all();
//		    dd($data);
		    $today = date("Y-m-d");
//		    dd($today);
		    $year = date("Y");
		    $days = $this->cal_days_in_year($year);
		    for ($i = 0; $i < sizeof($data); $i += 3) {
			$date = date_format(new \DateTime($data[$i + 2]), 'Y-m-d');


			if (strtolower($plans[$data[$i + 1]]) == 'yearly') {
			    if ($days < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
				array_push($users, $data[$i]);
			    }
			} elseif (strtolower($plans[$data[$i + 1]]) == 'halfyearly') {
			    if (intval($days / 2) < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
				array_push($users, $data[$i]);
			    }
			} elseif (strtolower($plans[$data[$i + 1]]) == 'quarterly') {
			    if (intval($days / 4) < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
				array_push($users, $data[$i]);
			    }
			} elseif (strtolower($plans[$data[$i + 1]]) == 'mothly') {
			    if (intval($days / 12) < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
				array_push($users, $data[$i]);
			    }
			}
		    }
		    if (strtolower($request->status) == "expired") {
			
			$roleusers = $users;
		    }
// 
		    if (strtolower($request->status) == "active") {
			
			$roleusers = array_diff($roleusers, $users);
			
			
		    }
		} elseif (strtolower($request->status) == "abouttoexpire") {

		    $data = \App\RoleUser::Where('role_id', $role_id)->select('user_id', 'role_plan_id', 'created_at')->get();
		    $plans = \App\RolePlans::Where('role_id', $role_id)->pluck('fee_type', 'id');
		    $data = collect($data->toArray())->flatten()->all();
		    $today = date("Y-m-d");
		    $year = date("Y");
		    $days = $this->cal_days_in_year($year);
		    for ($i = 0; $i < sizeof($data); $i += 3) {
			$date = date_format(new \DateTime($data[$i + 2]), 'Y-m-d');


			if (strtolower($plans[$data[$i + 1]]) == 'yearly') {
			    if (abs($days - intval(abs(strtotime($date) - strtotime($today)) / 86400)) <= 80) {
				array_push($users, $data[$i]);
			    }
			} elseif (strtolower($plans[$data[$i + 1]]) == 'half_yearly') {
			    if (abs(intval($days / 2) - intval(abs(strtotime($date) - strtotime($today)) / 86400)) <= 80) {
				array_push($users, $data[$i]);
			    }
			} elseif (strtolower($plans[$data[$i + 1]]) == 'quarterly') {

			    if (abs(intval($days / 4) - intval(abs(strtotime($date) - strtotime($today)) / 86400)) <= 80) {
				array_push($users, $data[$i]);
			    }
			} elseif (strtolower($plans[$data[$i + 1]]) == 'mothly') {
			    if (abs(intval($days / 12) - intval(abs(strtotime($date) - strtotime($today)) / 86400)) <= 80) {
				array_push($users, $data[$i]);
			    }
			}
		    }
		    $roleusers = $users;
		   
		    
		}
	    }
	    $users = User::wherein('id', $roleusers)->latest();
	    return Datatables::of($users)
			    ->addIndexColumn()
			    ->editColumn('payment_status', function($item)use($role_id) {
				try {
				    if ($item->parent_id != '0')
					return "Parent Will Pay subscription";

				    if ($item->payment_status == '')
					return 'Not Paid Yet ';
				    else if ($item->payment_status == 'AUTHORISED')
					return $item->payment_status;
				    else
					return $item->payment_status . " <button class='btn btn-info btn-sm sendPayment' title='send'  data-id=" . $item->id . " data-status='send'>Send Link Customer to Pay </button>";

//                                              if ($item->payment_status == 'accepted'):
//                                            return "Payment Transaction Date: 240920";
//                                        else:
//                                            return "<button class='btn btn-info btn-sm sendPayment' title='send'  data-id=" . $item->id . " data-status='send'>Send Link Customer to Pay </button>";
//                                        endif;
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->editColumn('parent_id', function($item) {
				try {
				    if ($item->parent_id == null || $item->parent_id == '0' || User::whereId($item->parent_id)->count() == '0'):
					return 'NAN';
				    else:
					return '<a target="_blank" href="' . route('users.show', $item->parent_id) . '">' . User::whereId($item->parent_id)->value('first_name') . '</a>';
				    endif;
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->editColumn('trainer_id', function($item) {
				try {
				    if ($item->trainer_id == null || $item->trainer_id == '0'):
					return 'NAN';
				    else:
					return '<a target="_blank" href="' . route('trainer-user.show', $item->trainer_id) . '">' . \App\TrainerUser::whereId($item->trainer_id)->value('first_name') . '</a>';
				    endif;
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('package', function($item)use($role_id) {
				try {

				    return $item->role->category;
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('feature', function($item)use($role_id) {
				try {
				    $res = json_decode($item, true);
				    $data = [];
				    foreach ($res['role']['permission'] as $feature) {
					$data[] = $feature['name'];
				    }
				    return implode(", ", $data);
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('subscription', function($item)use($role_id) {
				try {
				    $model = \DB::table('role_user')->where('role_id', $role_id)->where('user_id', $item->id)->get();
				    if ($model->isEmpty() != true)
					if ($model->first()->role_plan_id != null)
					    return \App\RolePlans::whereId($model->first()->role_plan_id)->first()->fee_type;
				    return 'nan';
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('payment_date', function($item)use($role_id) {
				try {
				    return $item->role->action_date;
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('joining_date', function($item)use($role_id) {
				try {
				    return $item->roles['0']['created_at'];
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('end_date', function($item)use($role_id) {
//                                dd($item->role->current_plan->fee_type);
				try {

				    $subscription_endDate = new Carbon\Carbon($item->role->action_date);
				    switch ($item->role->current_plan->fee_type):
					case'monthly':
					    $subscription_endDate = $subscription_endDate->addMonth();
//                                        dd('ss');
					    break;
					case'quarterly':
					    $subscription_endDate = $subscription_endDate->addMonths(3);
					    break;
					case'half_yearly':
					    $subscription_endDate = $subscription_endDate->addMonths(6);
					    break;
					case'yearly':
					    $subscription_endDate = $subscription_endDate->addMonths(12);
					    break;
				    endswitch;
//                                dd($subscription_endDate);
				    $subscription_end = new Carbon\Carbon($subscription_endDate);
				    return $subscription_end->diffForHumans();
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->addColumn('action', function($item)use($role_id) {
//                                $return = 'return confirm("Confirm delete?")';
				try {
				    $return = '';

				    if ($item->status == '0'):
					$return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
				    else:
					$return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
				    endif;

				    $return .= " <a  href=" . url('/admin/users/' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                         <a href=" . url('/admin/users/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>
                                          <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/users/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
				    $return .= "&nbsp; <a  href=" . url('/admin/bookings?created_by=' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View Bookings </button></a>";
				    return $return;
				} catch (\Exception $ex) {
				    return 'NAN';
				}
			    })
			    ->rawColumns(['action', 'feature', 'payment_status', 'parent_id', 'package', 'payment_date', 'joining_date', 'end_date'])
			    ->make(true);
	}

	if (isset($role_id))
	    if ($role_id != 1)
		$this->__rulesforindex += ['parent_id' => 'required'];
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
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function createWithRole(Request $request, $role_id) {
	return view('admin.users.create', compact('role_id'));
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
//            'middle_name' => 'required|alpha',
	    'last_name' => 'required|alpha',
	    'email' => 'required|string|max:255|email|unique:users',
	    'password' => 'required',
	    'mobile' => 'required|numeric',
//	    'birth_date' => 'required|date_format:Y-m-d|before:today',
//	    'emergency_contact_no' => 'required|numeric',
//            'image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450',
	    'emirate_image1' => 'required|mimes:jpg,jpeg,png',
//            'image' => 'required|mimes:jpg,jpeg,png|dimensions:width=360,height=450',
	    'emirate_image2' => 'required|mimes:jpg,jpeg,png',
		]
	);
	$data = $request->all();
//        dd($data);
	$data['password'] = bcrypt($data['password']);
	if (isset($data['trainer_services']))
	    $data['trainer_services'] = json_encode($data['trainer_services']);
	if ($request->hasfile('image')) {
	    $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
	    $request->file('image')->move(base_path() . '/public/uploads/users/', $imageName);
	    $data['image'] = $imageName;
	}
	if ($request->hasfile('emirate_image1')) {

	    $imageName1 = uniqid() . '.' . $request->file('emirate_image1')->getClientOriginalExtension();
	    $request->file('emirate_image1')->move(base_path() . '/public/uploads/emirateimages/', $imageName1);
	    $data['emirate_image1'] = $imageName1;
	}
	if (!empty($request->file('emirate_image2')))
	    $data['emirate_image2'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('emirate_image2'), public_path(User::$_imagePublicPath), true);
//        dd($request->role_id);
	$user = User::create($data);
	$user->assignRole($request->role_id, 'id');
	if (isset($request->role_plan))
	    \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $user->id)->update(['role_plan_id' => $request->role_plan]);
	if (isset($request->role_plan))
	    self::mailSend($data, $request);
	return redirect('admin/users/role/' . $request->role_id)->with('flash_message', ' User added!');
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
	$user = User::with('roles')->findOrFail($id);
	$user_roles = [];
	foreach ($user->roles as $role) {
	    $role_id = $role->id;
	    $user_roles[] = $role->name;
	}
	return view('admin.users.edit', compact('user', 'roles', 'user_roles', 'role_id'));
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
	$rules = ['first_name' => 'required'];
//        $rules = ['first_name' => 'required', 'email' => 'required|string|max:255|email|unique:users,email,' . $id,];
//        if ($request->has('image'))
//            $rules += ['image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450'];
	
	
	if ($request->has('password'))
	    $rules += ['password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/'];
	$this->validate($request, $rules);
	$data = $request->except('password');
	if ($request->has('password')) {
	    if (!empty($request->password))
		$data['password'] = bcrypt($request->password);
	}
	if ($request->hasfile('image')) {
	    $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
	    $request->file('image')->move(base_path() . '/public/uploads/users/', $imageName);
	    $data['image'] = $imageName;
	}
	if (isset($data['trainer_services']))
	    $data['trainer_services'] = json_encode($data['trainer_services']);
	$user = User::findOrFail($id);
	$user->update($data);
//        $user->roles()->detach();
//        foreach ($request->roles as $role) {
//            $user->assignRole('Customer');
//            $user->assignRole($role);
//        }
//dd('aa');

	return redirect('admin/users/role/' . $request->role_id)->with('flash_message', ' User Updated');
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
//        dd(User::whereId($user->id)->first()->role->current_plan->value('fee'));
	//Payment function start
	$userGet = \App\User::whereId($user->id)->first();
	if ($user->parent_id == '0'):
	    $price = $userGet->role->current_plan->fee;
	    if (!in_array($userGet->payment_status, \App\Booking::$_BookingApprovedStatus)):
		$booking = \App\Booking::create(['model_type' => 'users', 'model_id' => $request->id, 'created_by' => $user->id]);
		if ($userGet->trainer_id != '')
		    $price += \App\Http\Controllers\API\BookingController::$__trainer[$userGet->trainer_slot];
		\App\Helpers\ScapePanel::paymentFunction($user, $booking->id, $price);
	    else:
//            endif;
//            if (in_array($userGet->payment_status, \App\Booking::$_BookingApprovedStatus)):
		$to = \Carbon\Carbon::createFromFormat('Y-m-d', $user->role_expired_on);
		$from = \Carbon\Carbon::now();
		$diff_in_days = $to->diffInDays($from);
		if ($diff_in_days > 7):
		    $user->payment_status = 'PENDING';
		    $booking = \App\Booking::create(['model_type' => 'users', 'model_id' => $request->id, 'created_by' => $user->id]);
		    \App\Helpers\ScapePanel::paymentFunction($user, $booking->id, $price);
		endif;
	    endif;
	endif;
//        $paymentFunction = \App\Helpers\ScapePanel::paymentFunction($user, $booking->id, $price);
//        if ($paymentFunction == false)
//            return response()->json(["success" => true, 'message' => 'Something went wrong while sending payment link']);
//        \App\Http\Controllers\Admin\UsersController::mailSend(array_merge(User::whereId($user->id)->first()->toArray(), ['payment_href' => $paymentFunction]), $request);
//        //Payment function end
////        if ($user->payment_status != 'accepted')
////            return response()->json(["success" => false, 'message' => 'Customer has not paid Subscription yet, Kindly send link again as customer not paid yet.']);
	$user->status = $request->status == 'Block' ? '0' : '1';
	$user->save();

	return response()->json(["success" => true, 'message' => 'User updated!']);
    }

    public function sendPayment(Request $request) {
//        dd('dd');
	$data = User::whereId($request->id)->first()->toArray();
//        $user->status = $request->status == 'Block' ? '0' : '1';
//        $user->save();
	$request->email = $data['email'];
	self::mailSend($data, $request);
//        dd($mail);
	return response()->json(["success" => true, 'message' => 'User updated!']);
    }

    public static function mailSend($data, $request) {
//        dd($data,$request->email);
	try {

	    \Mail::send('emails.send_paymentlink', $data, function($message) use ($request) {
		$message->from(env('MAIL_FROM_ADDRESS'));
		$message->sender(env('MAIL_FROM_ADDRESS'));
		$message->to($request->email);
		$message->subject('Please subscribe for payment !');
	    });
	    return true;
	} catch (\Exception $ex) {
	    return false;
	}
    }

}
