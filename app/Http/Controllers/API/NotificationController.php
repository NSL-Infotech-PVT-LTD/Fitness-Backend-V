<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notification as MyModel;

class NotificationController extends ApiController
{
//    public function getItems(Request $request) {
//        $rules = ['search' => '', 'limit' => ''];
//        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
//        if ($validateAttributes):
//            return $validateAttributes;
//        endif;
//        // dd($category_id);
//        try {
////            $user = \App\User::find(Auth::user()->id);
//            $model = new MyModel();
//            $perPage = isset($request->limit) ? $request->limit : 20;
//
//            if (isset($request->search))
//                $model = $model->Where('title', 'LIKE', "%$request->search%")
//                        ->orWhere('body', 'LIKE', "%$request->search%")
//                        ->orWhere('data', 'LIKE', "%$request->search%");
//
//
//            $model = $model->where('action_id', \Auth::id())->select('id', 'title', 'body', 'data', 'created_by', 'created_at', 'action_id');
//            $model = $model->with('userDetail')->orderBy('created_at', 'desc');
//            return parent::success($model->paginate($perPage));
//        } catch (\Exception $ex) {
//            return parent::error($ex->getMessage());
//        }
//    }

//    public function deleteNotifications(Request $request) {
////        dd('s');
//        $rules = ['receiver_id' => 'required|exists:users,id'];
//        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
//        if ($validateAttributes):
//            return $validateAttributes;
//        endif;
//        try {
//            if (MyModel::where('target_id', $request->receiver_id)->get()->isEmpty() == true)
//                return parent::error('No notifications found for this player');
//
//            $model = MyModel::where('target_id', $request->receiver_id);
//            $model = $model->delete();
//            return parent::success('Notifications Successfully Deleted');
//        } catch (\Exception $ex) {
//            return parent::error($ex->getMessage());
//        }
//    }
    
//    public function notificationRead(Request $request) {
//        $rules = ['notification_id' => '', 'sender_id' => 'required', 'type' => 'required'];
//        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
//        if ($validateAttributes):
//            return $validateAttributes;
//        endif;
//        try {
//            $user = \App\User::findOrFail(\Auth::id());
//
//            $model = new \App\Notification();
//
//            $perPage = isset($request->limit) ? $request->limit : 20;
//
//            $notificationread = \App\Notification::where('data', 'LIKE', '%' . $request->type . '%')->where('target_id', \Auth::id());
//            $not = $notificationread->get();
//            $notificationId = [];
//            foreach ($not as $data):
//                if ($data->data->target_id == $request->sender_id):
//                    $notificationId[] = $data->id;
//                endif;
//            endforeach;
//            $notificationread = \App\Notification::whereIn('id', $notificationId)->update(['is_read' => '1']);
//            return parent::success(['message' => 'Notification mark Read', 'notification' => $notificationread]);
//        } catch (\Exception $ex) {
//            return parent::error($ex->getMessage());
//        }
//    }

    public function notificationCount(Request $request) {

        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = new \App\Notification();
            $model = $model->where('target_id', \Auth::id())->where('is_read', '0')->count();
            return parent::success(['notification_count' => $model]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    public function notifications(Request $request) 
    {
        $rules = ['limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $user = \App\User::findOrFail(\Auth::id());
            $model = new MyModel();
            $perPage = isset($request->limit) ? $request->limit : 20;
            
            $models = $model->where('target_id', \Auth::id())
                    ->with('customerDetail')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
            if(count($model) > 0) {
                return parent::success($models);
            } else {
                return parent::success(['message'=>'You have no notification.']);
            }
            
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    public function notificationRead(Request $request) 
    {
        $rules = ['id' => 'required|exists:notifications,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            $model = new MyModel();
            $model = $model->findOrFail($input['id']);
            $model->update([
                'is_read' => '1'
            ]);
            return parent::success(['message' => 'Notification Updated.']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
}
