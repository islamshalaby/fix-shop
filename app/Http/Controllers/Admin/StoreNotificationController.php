<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Helpers\APIHelpers;
use App\StoreNotification;
use App\Notification;
use App\Shop;

class StoreNotificationController extends AdminController{
    
    // get all notifications
    public function show(){
        $data['notifications'] = Notification::where('type', 2)->orderBy('id' , 'desc')->get();
        return view('admin.store_notifications' , ['data' => $data]);
    }

    // get notification details
    public function details(Request $request){
        $data['notification'] = Notification::find($request->id);
        return view('admin.store_notification_details' , ['data' => $data]);
    }

    // delete notification
    public function delete(Request $request){
        $notification = StoreNotification::find($request->id);
        if($notification){
            $notification->delete();
        }
        return redirect('admin-panel/store_notifications/show');
    }

    // type : get - get send notification page
    public function getsend(){
        return view('admin.store_notification_form');
    }

    // send notification and insert it in database
    public function send(Request $request){
        $notification = new Notification();
        if($request->file('image')){
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];    
            $image_new_name = $image_id.'.'.$image_format;
            $notification->image = $image_new_name;
            $image = $image_new_name;
        }else{
            $notification->image = null;
            $image = null;
        }

        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->type = 2;
        $notification->save();

        $users = Shop::select('id','fcm_token')->where('fcm_token' ,'!=' , null)->get();
        for($i =0; $i < count($users); $i++){
            $fcm_tokens[$i] = $users[$i]['fcm_token'];
            $user_notification = new StoreNotification();
            $user_notification->store_id = $users[$i]['id'];
            $user_notification->title = $request->title;
            $user_notification->body = $request->body;
            $user_notification->image = $image;
            $user_notification->save();            
        }
		
		$the_image = "https://res.cloudinary.com/dezsm0sg7/image/upload/w_200,q_100/v1581928924/".$notification->image;

        $notificationss = APIHelpers::send_notification($notification->title , $notification->body , $the_image , null , $fcm_tokens); 
        // dd($notificationss);   
        return redirect('admin-panel/store_notifications/show');
    }



    // resend notifications 
    public function resend(Request $request){
        $notification_id = $request->id;
        $notification = Notification::find($notification_id);

        $users_tokens = Shop::select('fcm_token')->get();
        $array_values = array_values((array)$users_tokens);
        $array_values = $array_values[0];
        APIHelpers::send_notification($notification->title , $notification->body , $notification->image , null , $array_values);
        return redirect()->back()->with('status', 'Sent succesfully');
    }

}