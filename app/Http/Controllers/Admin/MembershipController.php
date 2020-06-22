<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Membership;
use Illuminate\Http\Request;
use DataTables;
use DB;

class MembershipController extends Controller {

    protected $__rulesforindex = ['user_type' => 'required', 'category' => 'required', 'fee' => 'required', 'image' => 'required'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {

        if ($request->ajax()) {
            $model = Membership::with(['detail'])->groupBy('membership_details_id')->get();
//            dd($model->toArray());
            return Datatables::of($model)
                            ->addIndexColumn()
                            ->addColumn('image', function($item) {
                                return "<img width='50' src=" . url('uploads/membership/' . $item->detail->image) . ">";
                            })
                            ->addColumn('user_type', function($item) {
                                return $item->detail->user_type;
                            })
                            ->addColumn('category', function($item) {
                                return $item->detail->category;
                            })
                            ->editColumn('fee', function($item) {
//                                dd($item->fees['monthly_fee']);
//                                return $item->fees['monthly_fee'];
                                $return = '';
                                if (isset($item->fees['monthly_fee']))
                                    $return .= '<b>Monthly Fee:</b>' . $item->fees['monthly_fee'];
                                if (isset($item->fees['quaterly_fee']))
                                    $return .= '<br/><b>Qualterly Fee:</b>' . $item->fees['quaterly_fee'];
                                if (isset($item->fees['half_yearly_fee']))
                                    $return .= '<br/><b>Half Yearly Fee:</b>' . $item->fees['half_yearly_fee'];
                                if (isset($item->fees['yearly_fee']))
                                    $return .= '<br/><b>Yearly Fee:</b>' . $item->fees['yearly_fee'];
//                                dd($return);
                                return $return;
                            })
                            ->addColumn('action', function($item) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';

                                if ($item->status == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;

                                $return .= "  <a href=" . url('/admin/membership/' . $item->id) . " title='View Activity Plan'><button class='btn btn-info btn-sm'><i class='fas fa-folder' aria-hidden='true'></i> View </button></a>
                                    
                                        <a href=" . url('/admin/membership/' . $item->id . '/edit') . " title='Edit Activity Plan'><button class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt' aria-hidden='true'></i> Edit </button></a>"
                                        . "  <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/membership/' . $item->id) . "'><i class='fas fa-trash' aria-hidden='true'></i> Delete </button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image', 'fee'])
                            ->make(true);
        }
        return view('admin.membership.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.membership.create');
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
            'user_type' => 'required',
            'category' => 'required',
            'name' => 'required',
            'description' => 'required',
//            'image' => 'image|mimes:jpg,jpeg,png|dimensions:width=360,height=450',
        ]);
        $requestData = $request->only(['user_type', 'category', 'name', 'description', 'image']);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/membership/', $imageName);
            $requestData['image'] = $imageName;
        }
        $membershipDetail = \App\MembershipDetail::create($requestData);
        $data = [];
        foreach ($request->all() as $key => $reqdata):
            if (strpos($key, '_fee') != false):
//                dd(str_replace('_fee', '', $key));
                $data[] = ['membership_details_id' => $membershipDetail->id, 'fee_type' => str_replace('_fee', '', $key), 'fee' => $reqdata];
            endif;
        endforeach;
//        dd($data);
        Membership::insert($data);
        return redirect('admin/membership')->with('flash_message', 'Membership added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $membership = Membership::findOrFail($id);

        return view('admin.membership.show', compact('membership'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $membership = Membership::whereId($id)->with(['detail'])->first();
//        dd($membership->first()->detail);
        return view('admin.membership.edit', compact('membership'));
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
            'user_type' => 'required',
            'category' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => ''
        ]);
//        $requestData = $request->all();
//        dd($requestData);

        $membership = Membership::findOrFail($id);
//        dd($membership->membership_details_id);
        foreach ($request->all() as $key => $reqdata):
            if (strpos($key, '_fee') != false):
                $membershipId = Membership::where('membership_details_id', $membership->membership_details_id)->where('fee_type', str_replace('_fee', '', $key))->first()->id;
//            dd($membershipId);
                $membershipUpdate = Membership::findOrFail($membershipId);
                $membershipUpdate->update(['fee' => $reqdata]);
            endif;
        endforeach;
        $requestData = $request->only(['user_type', 'category', 'name', 'description', 'image']);
        if ($request->hasfile('image')) {
            $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/uploads/membership/', $imageName);
            $requestData['image'] = $imageName;
        }
        $membershipDetails = \App\MembershipDetail::findOrFail($membership->membership_details_id);
        $membershipDetails->update($requestData);

        return redirect('admin/membership')->with('flash_message', 'Membership updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Membership::destroy($id);

        return redirect('admin/membership')->with('flash_message', 'Membership deleted!');
    }

    public function changeStatus(Request $request) {
//        dd('dd');
        $activityplan = Membership::findOrFail($request->id);
        $activityplan->status = $request->status == 'Block' ? '0' : '1';
        $activityplan->save();
        return response()->json(["success" => true, 'message' => 'ActivityPlan updated!']);
    }

}
