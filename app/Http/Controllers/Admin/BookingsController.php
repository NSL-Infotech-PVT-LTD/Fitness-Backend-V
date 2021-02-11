<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Booking;
use Illuminate\Http\Request;
use DataTables;
use DB;

class BookingsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
//    protected $__rulesforindex = ['model_type' => 'required', 'model_id' => 'required', 'created_by' => 'required', 'created_at' => 'required'];
    protected $__rulesforindex = ['model_type' => 'required', 'created_by' => 'required', 'created_at' => 'required', 'payment_status' => 'required', 'type' => '', 'created_by' => ''];

    public function index(Request $request) {
        if ($request->ajax()) {
            $bookings = new Booking();
            if ($request->created_by != '')
                $bookings = $bookings->where('created_by', $request->created_by);
            $bookings = $bookings->latest();

//            dd($request->created_by);
            return Datatables::of($bookings)
                            ->addIndexColumn()
                            ->editColumn('created_by', function($item) {
                                return \App\User::whereId($item->created_by)->first()->full_name;
                            })
                            ->editColumn('model_type', function($item) {

                                switch ($item->model_type):
                                    case'trainer_users':
                                        return 'Trainer/' . $item->model_detail['first_name'];
                                        break;
                                    case'class_schedules':
                                        $res = json_decode($item->model_detail, true);
                                        return 'Group Class/' . $res['class_detail']['name'];
                                        break;
                                    default:
                                        return $item->model_type;
                                        break;
                                endswitch;
                            })
                            ->editColumn('model_id', function($item) {
                                return "<a href=" . url('admin/' . $item->model_type . '/' . $item->model_id) . ">" . $item->model_type . "</a>";
                            })
                            ->addColumn('type', function($item) {
                                $user = \App\User::where('id', $item->created_by)->first();
                                return isset($user->role->name) ? $user->role->name : 'NAN';
                            })
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';
                                $return .= "  <a href=" . url('/admin/bookings/' . $item->id) . " title='View bookings'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>";
                                if ($item->status == '0'):
                                    $return .= "<button traget-href=" . url('/admin/bookings/change-status/1/' . $item->id) . " class='btn btn-alt-success btn-sm changeStatus'><i class='fas fa-check' aria-hidden='true'></i> Accept Booking </button>";
                                    $return .= "<button traget-href=" . url('/admin/bookings/change-status/0/' . $item->id) . " class='btn btn-alt-danger btn-sm changeStatus'><i class='fas fa-times' aria-hidden='true'></i> Reject Booking </button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm'><i class='fas fa-check' aria-hidden='true'></i> Booking Confirmed </button>";
                                endif;
                                return $return;
                            })
                            ->rawColumns(['action', 'model_id', 'type'])
                            ->make(true);
        }
        return view('admin.bookings.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $this->validate($request, [
            'model_type' => 'required',
            'model_id' => 'required',
            'payment_status' => 'required'
        ]);
        $requestData = $request->all();

        Booking::create($requestData);

        return redirect('admin/bookings')->with('flash_message', 'Booking added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $booking = Booking::findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $booking = Booking::findOrFail($id);

        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'model_type' => 'required',
            'model_id' => 'required',
            'payment_status' => 'required'
        ]);
        $requestData = $request->all();

        $booking = Booking::findOrFail($id);
        $booking->update($requestData);

        return redirect('admin/bookings')->with('flash_message', 'Booking updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Booking::destroy($id);

        return redirect('admin/bookings')->with('flash_message', 'Booking deleted!');
    }

    public function changeStatus(Request $request, $status, $id) {
        $model = Booking::findOrFail($id);
//        dd($model->toArray());
        if ($status == '0'):
            Booking::destroy($id);
            $statusMSG = 'Rejected';
        else:
            $model->status = $status == '1' ? '1' : '0';
            $model->save();
            $statusMSG = 'Accepted';

            if ($model->model_type == 'trainer_users'):
                \App\Http\Controllers\API\ApiController::pushNotifications(['title' => 'Your Trainer Sessions is been approved', 'body' => 'Now you can have PT with trainer you booked', 'data' => ['target_id' => $id, 'target_model' => 'Booking', 'data_type' => 'Booking']], $model->created_by, TRUE);
                \App\Http\Controllers\API\ApiController::pushNotifications(['title' => 'Booking Received', 'body' => 'Kindly Schdeule slots for customer', 'data' => ['target_id' => $id, 'target_model' => 'Booking', 'data_type' => 'Booking']], $model->model_id, TRUE, ['template_name' => 'notify', 'subject' => 'Kindly Schdeule slots for customer', 'customData' => ['notifyMessage' => 'Booking has been approved by Admin, Kindly Schdeule slots for customer.']]);
                $user = \App\User::findOrFail($model->created_by);
                $user->trainer_slot = (int) $user->trainer_slot + $model->hours;
                $user->trainer_id = $user->model_id;
                $user->save();
            endif;
            if ($model->model_type == 'sessions'):
                \App\Http\Controllers\API\ApiController::pushNotifications(['title' => 'Your Sessions is been approved', 'body' => 'Now you can book class schedule', 'data' => ['target_id' => $id, 'target_model' => 'Booking', 'data_type' => 'Booking']], $model->created_by, TRUE);
                $user = \App\User::findOrFail($model->created_by);
                $user->my_sessions = $user->my_sessions + $model->session;
                $user->save();
            endif;
        endif;
        $modelBookedBy = $model->created_by;
//        if ($model->model_type == 'class_schedules') {
//            $name = $model->model_detail->trainer['first_name'] . ' ' . $model->model_detail->trainer['middle_name'] . ' ' . $model->model_detail->trainer['last_name'];
//            $email = $model->model_detail->trainer['email'];
//            
//        } elseif ($model->model_type == 'trainer_users') {
//            $name = $model->model_detail['first_name'] . ' ' . $model->model_detail['middle_name'] . ' ' . $model->model_detail['last_name'];
//            $email = $model->model_detail['email'];
//        } else {
//            return response()->json(["success" => true, 'message' => 'Booking Removed Successfully']);
//        }
//            parent::pushNotifications(['title' => self::$__BookingStatus['pending']['customer']['title'], 'body' => self::$__BookingStatus['pending']['customer']['body'], 'data' => ['target_id' => $address['id'], 'target_model' => 'Booking', 'data_type' => 'Booking', 'booking_id' => $address['id']]], \Auth::id(), TRUE);//send mail to user as a feedback    
//
//        $dataM = ['subject' => 'Reject your booking', 'name' => $name, 'to' => $email];
//
//        \Mail::send('emails.notify', $dataM, function($message) use ($dataM) {
//            $message->to($dataM['to']);
//            $message->subject($dataM['subject']);
//        });
        //ENDS
        //Send to the Customer
        \App\Http\Controllers\API\ApiController::pushNotifications(['title' => 'Booking ' . $statusMSG, 'body' => 'Booking ' . $statusMSG, 'data' => ['target_id' => $id, 'target_model' => 'Booking', 'data_type' => 'Booking']], $modelBookedBy, TRUE, ['template_name' => 'notify', 'subject' => 'Your Booking is ' . $statusMSG, 'customData' => ['notifyMessage' => 'Your booking has been ' . $statusMSG . ' by volt.']]);
        return response()->json(["success" => true, 'message' => 'Booking ' . $statusMSG . ' Successfully !!!']);
    }

}
