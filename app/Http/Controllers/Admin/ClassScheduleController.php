<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ClassSchedule;
use Illuminate\Http\Request;
use DataTables;
use DB;

class ClassScheduleController extends Controller {

    protected $__rulesforindex = ['class_type' => 'required', 'start_date' => 'required'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $class = ClassSchedule::all();
            return Datatables::of($class)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                return "<img width='50' src=" . url('uploads/class/' . $item->image) . ">";
                            })
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/class-schedule/' . $item->id) . " title='View Class Plan'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/class-schedule/' . $item->id . '/edit') . " title='Edit Class'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/class-schedule/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.class-schedule.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.class-schedule.create');
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
            'class_type' => 'required',
            'start_date' => 'required',
//            'end_date' => 'required',
            'repeat_on' => '',
            'start_time' => 'required',
            'duration' => 'required',
            'trainer_id' => 'required',
            'cp_spots' => 'required',
            'capacity' => 'required'
        ]);
        $requestData = $request->all();
//dd($requestData);
        if (isset($requestData['repeat_on']))
            $requestData['repeat_on'] = json_encode($requestData['repeat_on']);
        ClassSchedule::create($requestData);

        return redirect('admin/class-schedule')->with('flash_message', 'ClassSchedule added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $classschedule = ClassSchedule::findOrFail($id);

        return view('admin.class-schedule.show', compact('classschedule'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $classschedule = ClassSchedule::findOrFail($id);

        return view('admin.class-schedule.edit', compact('classschedule'));
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
            'class_type' => 'required',
            'start_date' => 'required',
            'end_date' => '',
            'repeat_on' => '',
            'start_time' => 'required',
            'duration' => 'required',
            'trainer_id' => 'required',
            'cp_spots' => '',
            'capacity' => ''
        ]);
        $requestData = $request->all();

        $classschedule = ClassSchedule::findOrFail($id);
        if (isset($requestData['repeat_on']))
            $requestData['repeat_on'] = json_encode($requestData['repeat_on']);
        $classschedule->update($requestData);

        return redirect('admin/class-schedule')->with('flash_message', 'ClassSchedule updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        ClassSchedule::destroy($id);

        return redirect('admin/class-schedule')->with('flash_message', 'ClassSchedule deleted!');
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $class = ClassSchedule::findOrFail($request->id);
        $class->status = $request->status == 'Block' ? '0' : '1';
        $class->save();
        return response()->json(["success" => true, 'message' => 'Class updated!']);
    }

}
