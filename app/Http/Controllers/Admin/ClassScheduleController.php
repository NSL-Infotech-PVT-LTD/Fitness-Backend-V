<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ClassSchedule;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $classschedule = ClassSchedule::where('class_type', 'LIKE', "%$keyword%")
                ->orWhere('start_date', 'LIKE', "%$keyword%")
                ->orWhere('end_date', 'LIKE', "%$keyword%")
                ->orWhere('repeat_on', 'LIKE', "%$keyword%")
                ->orWhere('start_time', 'LIKE', "%$keyword%")
                ->orWhere('duration', 'LIKE', "%$keyword%")
                ->orWhere('class_id', 'LIKE', "%$keyword%")
                ->orWhere('trainer_id', 'LIKE', "%$keyword%")
                ->orWhere('cp_spots', 'LIKE', "%$keyword%")
                ->orWhere('capacity', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $classschedule = ClassSchedule::latest()->paginate($perPage);
        }

        return view('admin.class-schedule.index', compact('classschedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.class-schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'class_type' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'repeat_on' => 'required',
			'start_time' => 'required',
			'duration' => 'required',
			'trainer_id' => 'required',
			'cp_spots' => 'required',
			'capacity' => 'required'
		]);
        $requestData = $request->all();
        
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
    public function show($id)
    {
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
    public function edit($id)
    {
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'class_type' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'repeat_on' => 'required',
			'start_time' => 'required',
			'duration' => 'required',
			'trainer_id' => 'required',
			'cp_spots' => 'required',
			'capacity' => 'required'
		]);
        $requestData = $request->all();
        
        $classschedule = ClassSchedule::findOrFail($id);
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
    public function destroy($id)
    {
        ClassSchedule::destroy($id);

        return redirect('admin/class-schedule')->with('flash_message', 'ClassSchedule deleted!');
    }
}
