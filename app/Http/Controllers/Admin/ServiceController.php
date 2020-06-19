<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ApiController;
use App\Service;
use Illuminate\Http\Request;
use DataTables;
use DB;

class ServiceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View


      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public static $_mediaBasePath = 'uploads/services/';
    protected $__rulesforindex = ['name' => 'required', 'description' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $service = Service::all();
            return Datatables::of($service)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/service/' . $item->id) . " title='View Activity Plan'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/service/' . $item->id . '/edit') . " title='Edit Activity Plan'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/service/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.service.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.service.create');
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
//            'price' => 'required|numeric',
//            'image' => 'required',
//            'description' => 'required'
        ]);

        $requestData = $request->all();
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/services/', $imageName);
            $requestData['image'] = $imageName;
        }
        Service::create($requestData);

        return redirect('admin/service')->with('flash_message', 'Service added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $service = Service::findOrFail($id);

        return view('admin.service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $service = Service::findOrFail($id);
        $service = Service::select('id', 'name', 'price', 'image', 'description')->findOrFail($id);

        return view('admin.service.edit', compact('service'));
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
//            'price' => 'required',
//            'image' => 'required',
//            'description' => 'required'
        ]);
        $requestData = $request->all();

        $service = Service::findOrFail($id);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/services/', $imageName);
            $requestData['image'] = $imageName;
        }
        $service->update($requestData);

        return redirect('admin/service')->with('flash_message', 'Service updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        if (Service::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $service = Service::findOrFail($request->id);
        $service->status = $request->status == 'Block' ? '0' : '1';
        $service->save();
        return response()->json(["success" => true, 'message' => 'Service updated!']);
    }

}
