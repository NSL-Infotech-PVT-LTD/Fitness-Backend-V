<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActivityPlan;
use Illuminate\Http\Request;
use DataTables;
use DB;

class ActivityPlanController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
   
    protected $__rulesforindex = ['name' => 'required', 'description' => 'required'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $activityplan = ActivityPlan::all();
            return Datatables::of($activityplan)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                               if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/activity-plan/' . $item->id) . " title='View Activity Plan'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/activity-plan/' . $item->id . '/edit') . " title='Edit Activity Plan'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/activity-plan/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.activity-plan.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.activity-plan.create');
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
            'default_price' => 'required',
            'image' => 'required',
            'description' => 'required'
        ]);
        $requestData = $request->all();

        ActivityPlan::create($requestData);

        return redirect('admin/activity-plan')->with('flash_message', 'ActivityPlan added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $activityplan = ActivityPlan::findOrFail($id);

        return view('admin.activity-plan.show', compact('activityplan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $activityplan = ActivityPlan::findOrFail($id);

        return view('admin.activity-plan.edit', compact('activityplan'));
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
            'default_price' => 'required',
            'image' => 'required',
            'description' => 'required'
        ]);
        $requestData = $request->all();

        $activityplan = ActivityPlan::findOrFail($id);
        $activityplan->update($requestData);

        return redirect('admin/activity-plan')->with('flash_message', 'ActivityPlan updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
   public function destroy($id) {
        if (ActivityPlan::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }
    
    public function changeStatus(Request $request) {
//        dd('dd');
        $activityplan = ActivityPlan::findOrFail($request->id);
        $activityplan->status = $request->status == 'Block' ? '0' : '1';
        $activityplan->save();
        return response()->json(["success" => true, 'message' => 'ActivityPlan updated!']);
    }

}
