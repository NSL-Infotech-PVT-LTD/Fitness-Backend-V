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
    protected $__rulesforindex = ['model_type' => 'required', 'model_id' => 'required', 'payment_status' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $bookings = Booking::all();
            return Datatables::of($bookings)
                            ->addIndexColumn()
                            ->editColumn('model_id', function($item) {
                                return "<a href=" . url('admin/' . $item->model_type . '/' . $item->model_id) . ">" . $item->model_type . "</a>";
                            })
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';
                                $return .= "  <a href=" . url('/admin/bookings/' . $item->id) . " title='View bookings'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>";
                                return $return;
                            })
                            ->rawColumns(['action','model_id'])
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

}
