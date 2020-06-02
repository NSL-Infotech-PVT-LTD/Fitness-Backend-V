<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes;
use Illuminate\Http\Request;
use DataTables;
use DB;

class ClassController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    protected $__rulesforindex = ['name' => 'required', 'image' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $class = Classes::all();
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

                                $return .= "  <a href=" . url('/admin/class/' . $item->id) . " title='View Class Plan'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/class/' . $item->id . '/edit') . " title='Edit Class'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/class/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action','image'])
                            ->make(true);
        }
        return view('admin.class.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.class.create');
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
            'price' => 'required|numeric',
            'image' => 'required',
            'description' => 'required'
        ]);
        $requestData = $request->all();
        $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(base_path() . '/public/uploads/class/', $imageName);
        $requestData['image'] = $imageName;

        Classes::create($requestData);

        return redirect('admin/class')->with('flash_message', 'Class added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $class = Classes::findOrFail($id);

        return view('admin.class.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $class = Classes::findOrFail($id);

        return view('admin.class.edit', compact('class'));
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
            'price' => 'required',
//            'image' => 'required',
            'description' => 'required'
        ]);
        $requestData = $request->all();

        $class = Classes::findOrFail($id);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/class/', $imageName);
            $requestData['image'] = $imageName;
        }
        $class->update($requestData);

        return redirect('admin/class')->with('flash_message', 'Class updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        if (Classes::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $class = Classes::findOrFail($request->id);
        $class->status = $request->status == 'Block' ? '0' : '1';
        $class->save();
        return response()->json(["success" => true, 'message' => 'Class updated!']);
    }

}
