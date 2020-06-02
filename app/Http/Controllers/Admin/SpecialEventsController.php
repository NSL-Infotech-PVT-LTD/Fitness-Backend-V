<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SpecialEvent;
use Illuminate\Http\Request;
use DataTables;
use DB;

class SpecialEventsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    protected $__rulesforindex = ['name' => 'required', 'description' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $specialevents = SpecialEvent::all();
            return Datatables::of($specialevents)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                return "<img width='50' src=" . url('uploads/specialevents/' . $item->image) . ">";
                            })
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/special-events/' . $item->id) . " title='View Special Events'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/special-events/' . $item->id . '/edit') . " title='Edit Special Events'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/special-events/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.special-events.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.special-events.create');
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
        $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/uploads/specialevents/', $imageName);
        $requestData['image'] = $imageName;

        SpecialEvent::create($requestData);

        return redirect('admin/special-events')->with('flash_message', 'SpecialEvent added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $specialevent = SpecialEvent::findOrFail($id);

        return view('admin.special-events.show', compact('specialevent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $specialevent = SpecialEvent::findOrFail($id);

        return view('admin.special-events.edit', compact('specialevent'));
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

        $specialevent = SpecialEvent::findOrFail($id);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/specialevents/', $imageName);
            $requestData['image'] = $imageName;
        }
        $specialevent->update($requestData);

        return redirect('admin/special-events')->with('flash_message', 'SpecialEvent updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        if (SpecialEvent::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $specialevent = SpecialEvent::findOrFail($request->id);
        $specialevent->status = $request->status == 'Block' ? '0' : '1';
        $specialevent->save();
        return response()->json(["success" => true, 'message' => 'SpecialEvent updated!']);
    }

}
