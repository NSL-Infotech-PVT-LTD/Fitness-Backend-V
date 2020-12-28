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

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $__rulesforindex = ['first_name' => 'required', 'last_name' => 'required', 'mobile' => 'required', 'email' => 'required', 'payment_status' => 'required','subscription' => 'required','payment_date'=>'','joining_date' => '','end_date' => '','trainer_id'=>'trainer_id'];
//    protected $__rulesforindex = ['first_name' => 'required', 'last_name' => 'required', 'mobile' => 'required', 'email' => 'required', 'payment_status' => 'required', 'package' => '','feature' => '', 'subscription' => 'required','payment_date'=>'','joining_date' => '','end_date' => ''];

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
//            dd($roleusers);
            $users = User::wherein('id', $roleusers)->latest();
//            dd(json_decode($users)->role->permission);
            return Datatables::of($users)
                            ->addIndexColumn()
                            ->editColumn('payment_status', function($item)use($role_id) {
                                if ($item->parent_id == null):
                                    if ($item->payment_status == 'accepted'):
                                        return "Payment Transaction Date: 240920";
                                    else:
                                        return "<button class='btn btn-info btn-sm sendPayment' title='send'  data-id=" . $item->id . " data-status='send'>Send Link Customer to Pay </button>";
                                    endif;
                                else:
                                    return "Parent Will Pay subscription";
                                endif;
                            })
                            ->editColumn('parent_id', function($item) {
                                if ($item->parent_id == null || $item->parent_id == '0'):
                                    return 'NAN';
                                else:
                                    return '<a target="_blank" href="' . route('users.show', $item->parent_id) . '">' . User::whereId($item->parent_id)->first()->first_name . '</a>';
                                endif;
                            })
                            ->editColumn('trainer_id', function($item) {
                                if ($item->trainer_id == null || $item->trainer_id == '0'):
                                    return 'NAN';
                                else:
                                    return '<a target="_blank" href="' . route('trainer-user.show', $item->trainer_id) . '">' . \App\TrainerUser::whereId($item->trainer_id)->value('first_name') . '</a>';
                                endif;
                            })
                            ->addColumn('package', function($item)use($role_id) {
                                return $item->role->category;
                            })
                            ->addColumn('feature', function($item)use($role_id) {
                                $res = json_decode($item, true);
                                $data = [];
                                foreach($res['role']['permission'] as $feature) {
                                    $data[] = $feature['name'];
                                }
                                return implode(", ",$data);
                            })
                            ->addColumn('subscription', function($item)use($role_id) {
                                $model = \DB::table('role_user')->where('role_id', $role_id)->where('user_id', $item->id)->get();
                                if ($model->isEmpty() != true)
                                    if ($model->first()->role_plan_id != null)
                                        return \App\RolePlans::whereId($model->first()->role_plan_id)->first()->fee_type;
                                return 'nan';
                            })
                            ->addColumn('payment_date', function($item)use($role_id) {
                                return $item->role->action_date;
                            })
                            ->addColumn('joining_date', function($item)use($role_id) {
                                return $item->roles['0']['created_at'];
                            })
                            ->addColumn('end_date', function($item)use($role_id) {
                                $subscription_endDate = new Carbon\Carbon($item->roles['0']['action_date']);
                                switch ($item->roles['0']['current_plan']['fee_type']):
                                    case'Monthly':
                                        $subscription_endDate = $subscription_endDate->addMonth();
//                                        dd('ss');
                                        break;
                                    case'Quarterly':
                                        $subscription_endDate = $subscription_endDate->addMonths(3);
                                        break;
                                    case'Half yearly':
                                        $subscription_endDate = $subscription_endDate->addMonths(6);
                                        break;
                                    case'Yearly':
                                        $subscription_endDate = $subscription_endDate->addMonths(12);
                                        break;
                                endswitch;
//                                dd($subscription_endDate);
                                $subscription_end = new Carbon\Carbon($subscription_endDate);
                                return $subscription_end->diffForHumans();
                            })
                            ->addColumn('action', function($item)use($role_id) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= " <a  href=" . url('/admin/users/' . $item->id) . " title='View User'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                         <a href=" . url('/admin/users/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>
                                          <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/users/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";

                                return $return;
                            })
                            ->rawColumns(['action','feature', 'payment_status', 'parent_id', 'package','payment_date','joining_date','end_date'])
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
            'birth_date' => 'required|date_format:Y-m-d|before:today',
            'emergency_contact_no' => 'required|numeric',
//            'image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450',
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
//        dd($request->role_id);
        $user = User::create($data);
        $user->assignRole($request->role_id, 'id');
        if (isset($request->role_plan))
            \DB::table('role_user')->where('role_id', $request->role_id)->where('user_id', $user->id)->update(['role_plan_id' => $request->role_plan]);
        if (isset($request->role_plan))
            self::mailSend($data, $request);
        return redirect('admin/users/role/' . $request->role_id)->with('flash_message', $role . ' User added!');
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

//        if ($user->payment_status != 'accepted')
//            return response()->json(["success" => false, 'message' => 'Customer has not paid Subscription yet, Kindly send link again as customer not paid yet.']);
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
        \Mail::send('emails.send_paymentlink', $data, function($message) use ($request) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->sender(env('MAIL_FROM_ADDRESS'));
            $message->to($request->email);
            $message->subject('Please subscribe for payment !');
        });
        return true;
    }

}
