<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tournament;
use Illuminate\Http\Request;
use DataTables;
use DB;

class TournamentsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public static $_mediaBasePath = 'uploads/tournament/';
    protected $__rulesforindex = ['name' => 'required', 'price' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $tournament = Tournament::all();
            return Datatables::of($tournament)
                            ->addIndexColumn()
                            ->editColumn('price', function($item) {
                                return ' <i class="fa fa-' . config('app.stripe_default_currency') . '" aria-hidden="true"></i> ' . $item->price;
                            })
                            ->addColumn('enrollments', function($item) {
                                $return = "<a href=" . url('/admin/mydata/' . $item->id) . " title='View Enrollments'>View Enrollments</a>";
                                return $return;
                            })
                            ->addColumn('action', function($item) {
                                $return = '';
//
                                if ($item->state == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
                                $return .= " <a href=" . url('/admin/tournament/' . $item->id) . " title='View Tournament'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>"
                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/tournament/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'enrollments','price'])
                            ->make(true);
        }
        return view('admin.tournament.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    public function indexxx(Request $request) {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $tournament = Tournament::where('name', 'LIKE', "%$keyword%")
                            ->orWhere('image', 'LIKE', "%$keyword%")
                            ->orWhere('description', 'LIKE', "%$keyword%")
                            ->orWhere('price', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $tournament = Tournament::latest()->paginate($perPage);
        }

        return view('admin.tournament.index', compact('tournament'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.tournament.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {

        $requestData = $request->all();
        $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $requestData['prize_image'] = ApiController::__uploadImage($request->file('prize_image'), public_path(self::$_mediaBasePath));
        Tournament::create($requestData);

        return redirect('admin/tournament')->with('flash_message', 'Tournament added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $tournament = Tournament::findOrFail($id);

        return view('admin.tournament.show', compact('tournament'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $tournament = Tournament::findOrFail($id);

        return view('admin.tournament.edit', compact('tournament'));
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

        $requestData = $request->all();

        $tournament = Tournament::findOrFail($id);
        if (isset($request->image))
            $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));

        $tournament->update($requestData);

        return redirect('admin/tournament')->with('flash_message', 'Tournament updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    
    
      public function destroy($id) {
        if (Tournament::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

    public function changeStatus(Request $request) {
//        dd('ss');
        $tournament = Tournament::findOrFail($request->id);
        $tournament->state = $request->status == 'Block' ? '0' : '1';
        $tournament->save();
        return response()->json(["success" => true, 'message' => 'Tournament updated!']);
    }

}
