<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tournament;
use Illuminate\Http\Request;

class TournamentsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public static $_mediaBasePath = 'uploads/tournament/';

    public function index(Request $request) {
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
        Tournament::destroy($id);

        return redirect('admin/tournament')->with('flash_message', 'Tournament deleted!');
    }

}
