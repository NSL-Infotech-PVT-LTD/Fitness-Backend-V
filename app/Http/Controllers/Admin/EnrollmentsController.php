<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\EnrollTournaments;
use Illuminate\Http\Request;
use Datatable;
use App\User;

class EnrollmentsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function enrollmentByid(Request $request, $id) {

        $perPage = 25;


        $enrollment = EnrollTournaments::where('tournament_id', $id)->with(['userdetails','allImages'])->get();
        

//       dd($enrollment->toArray());  
//           $customer = User::where('id',$enrollment->customer_id)->value('name');




        return view('admin.enrollments.index', compact('enrollment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.enrollments.create');
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
        $enrollment = EnrollTournaments::findOrFail($id);

        return view('admin.enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $enrollment = EnrollTournaments::findOrFail($id);

        return view('admin.enrollments.edit', compact('enrollment'));
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
        EnrollTournaments::destroy($id);

        return redirect('admin/enrollments')->with('flash_message', 'Enrollment deleted!');
    }

    public function winnerstatus(Request $request) {
//dd($request->value);
        $status = EnrollTournaments::findOrFail($request->status);
//dd($request->value);
        if ($request->value == 'winner') {
            $status->status = $request->value == 'winner' ? '0' : '1';
        } else if ($request->value == 'loser') {
            $status->status = $request->value == 'loser' ? '1' : '0';
        }
//        dd($status);
        $status->save();
        return response()->json(["success" => true, 'message' => 'Enrollment updated!']);
    }
    
    
}
