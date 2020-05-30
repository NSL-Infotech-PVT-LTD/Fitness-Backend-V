<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\TrainingDetail;
use Illuminate\Http\Request;
use DataTables;
use DB;

class TrainingDetailController extends Controller
{
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
            $trainingdetail = TrainingDetail::all();
            return Datatables::of($trainingdetail)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                               if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/training-detail/' . $item->id) . " title='View Training detail'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/training-detail/' . $item->id . '/edit') . " title='Edit Training detail'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/training-detail/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.training-detail.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.training-detail.create');
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
			'name' => 'required',
			'default_price' => 'required',
			'image' => 'required',
			'description' => 'required'
		]);
        $requestData = $request->all();
        
        TrainingDetail::create($requestData);

        return redirect('admin/training-detail')->with('flash_message', 'TrainingDetail added!');
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
        $trainingdetail = TrainingDetail::findOrFail($id);

        return view('admin.training-detail.show', compact('trainingdetail'));
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
        $trainingdetail = TrainingDetail::findOrFail($id);

        return view('admin.training-detail.edit', compact('trainingdetail'));
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
			'name' => 'required',
			'default_price' => 'required',
			'image' => 'required',
			'description' => 'required'
		]);
        $requestData = $request->all();
        
        $trainingdetail = TrainingDetail::findOrFail($id);
        $trainingdetail->update($requestData);

        return redirect('admin/training-detail')->with('flash_message', 'TrainingDetail updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
        */
   public function destroy($id) {
        if (TrainingDetail::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }
    
    public function changeStatus(Request $request) {
//        dd('dd');
        $trainingdetail = TrainingDetail::findOrFail($request->id);
        $trainingdetail->status = $request->status == 'Block' ? '0' : '1';
        $trainingdetail->save();
        return response()->json(["success" => true, 'message' => 'TrainingDetail updated!']);
    }
}
