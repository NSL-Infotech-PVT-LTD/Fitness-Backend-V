<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Event;
use Illuminate\Http\Request;
use DataTables;
use DB;

class EventsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    protected $__rulesforindex = ['name' => 'required', 'description' => 'required','special'=>'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $events = Event::all();
            return Datatables::of($events)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                return "<img width='50' src=" . url('uploads/events/' . $item->image) . ">";
                            })
                            ->addColumn('special', function($item) {
                                $return = '';
                                if ($item->special == '0'):
                                    $return = "<button class='btn btn-danger btn-sm markspecial' title='UnBlock'  data-id=" . $item->id . " data-status='Special'><i class='fa fa-star-o fa-3' aria-hidden='true'></i>
 </button>";
                                else:
                                    $return = "<button class='btn btn-success btn-sm markspecial' title='Block' data-id=" . $item->id . " data-status='UnSpecial' ><i class='fa fa-star fa-3' aria-hidden='true'></button>";
                                endif;
                                return $return;
                            })
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/events/' . $item->id) . " title='View Events'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/events/' . $item->id . '/edit') . " title='Edit Events'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/events/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image','special'])
                            ->make(true);
        }
        return view('admin.events.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.events.create');
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
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
            'start_date' => 'required|date_format:Y-m-d|after:today',
            'end_date' => 'required|date_format:Y-m-d|after:today'
        ]);
        $requestData = $request->all();
        $requestData = $request->all();
        $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/uploads/events/', $imageName);
        $requestData['image'] = $imageName;

        Event::create($requestData);

        return redirect('admin/events')->with('flash_message', 'Event added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $event = Event::findOrFail($id);

        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $event = Event::findOrFail($id);

        return view('admin.events.edit', compact('event'));
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
            'name' => 'required',
//            'image' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        $requestData = $request->all();

        $event = Event::findOrFail($id);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/events/', $imageName);
            $requestData['image'] = $imageName;
        }
        $event->update($requestData);

        return redirect('admin/events')->with('flash_message', 'Event updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        if (Event::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $event = Event::findOrFail($request->id);
        $event->status = $request->status == 'Block' ? '0' : '1';
        $event->save();
        return response()->json(["success" => true, 'message' => 'Event updated!']);
    }

    public function MarkSpecial(Request $request) {
//        dd('dd');
        $event = Event::findOrFail($request->id);
//        dd($request->status);
        $event->special = $request->status == 'Special' ? '1' : '0';
//        dd($event->special);
        $event->save();
        return response()->json(["success" => true, 'message' => 'Event updated!']);
    }

}
