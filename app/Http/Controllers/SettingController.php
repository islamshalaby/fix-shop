<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use JD\Cloudder\Facades\Cloudder;
use Cloudinary\Api\Upload\UploadApi;
use App\Setting;
use App\Seller;


class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['joinRequest', 'getwhatsapp', 'social_media']]);
    }

    public function getappnumber(Request $request){
        $setting = Setting::select('phone')->find(1);
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $setting['phone'] , $request->lang);
        return response()->json($response , 200);
    }

    public function getwhatsapp(Request $request){
        $setting = Setting::select('app_phone')->find(1);
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $setting['app_phone'] , $request->lang);
        return response()->json($response , 200);
    }

    // seller join request
    public function joinRequest(Request $request) {
        
        $post = $request->all();
        $validator = Validator::make($post, [
            'name' => 'required',
            'shop' => 'required',
            'phone' => 'required|unique:sellers,phone',
            'id_number' => 'required|unique:sellers,id_number',
            'instagram' => 'required|unique:sellers,instagram',
            'account_number' => 'required',
            'front_image' => 'required',
            'back_image' => 'required'
            ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة او يوجد طلب بالفعل من هذا البائع' , null , $request->lang);
            return response()->json($response , 406);
        }
        
        $image = $request->front_image;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $front_image = $request->front_image;
        // Cloudder::uploadVideo($front_image,null, ['resource_type' => 'video',"eager_async" => TRUE, "eager" => ["format"=>"mp4","width"=>1280, "height"=>720, "crop"=>"limit", "duration"=>"7200p"], 'chunk_size' => 6000000]);
        Cloudder::upload($front_image, null);
        $front_imageereturned = Cloudder::getResult();
        $front_image_id = $front_imageereturned['public_id'];
        $front_image_format = $front_imageereturned['format'];    
        $front_image_new_name = $front_image_id.'.'.$front_image_format;
        $post['front_image'] = $front_image_new_name;
        

        $back_image = $request->back_image;
        Cloudder::upload($back_image, null);
        $iback_imagereturned = Cloudder::getResult();
        $iback_image_id = $iback_imagereturned['public_id'];
        $back_image_format = $iback_imagereturned['format'];    
        $back_image_new_name = $iback_image_id.'.'.$back_image_format;
        $post['back_image'] = $back_image_new_name;
        
        
        // dd("pppppp");
        Seller::create($post);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , null , $request->lang);
        return response()->json($response , 200);
    }

    // social media links
    public function social_media(Request $request) {
        $data = Setting::select('instegram', 'twitter', 'snap_chat')->find(1);
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}