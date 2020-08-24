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
    protected $__rulesforindex = ['model_type' => 'required', 'created_by' => 'required', 'created_at' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $bookings = Booking::all();
            return Datatables::of($bookings)
                            ->addIndexColumn()
                            ->editColumn('created_by', function($item) {
                                return \App\User::whereId($item->created_by)->first()->full_name;
                            })
                            ->editColumn('model_type', function($item) {
                                switch ($item->model_type):
                                    case'trainer_users':
                                        return 'Trainer';
                                        break;
                                    case'class_schedules':
                                        return 'Group Class';
                                        break;
                                    default:
                                        return $item->model_type;
                                        break;
                                endswitch;
                            })
                            ->editColumn('model_id', function($item) {
                                return "<a href=" . url('admin/' . $item->model_type . '/' . $item->model_id) . ">" . $item->model_type . "</a>";
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
                            ->rawColumns(['action', 'model_id'])
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
//        dd('dd');
        $user = Booking::findOrFail($id);
        if ($status == '0'):
            Booking::destroy($id);
            return response()->json(["success" => true, 'message' => 'Booking Removed Successfully']);
        endif;
        $user->status = $status == '1' ? '1' : '0';
        $user->save();
        return response()->json(["success" => true, 'message' => 'Booking Confirmed Successfully']);
    }

}
