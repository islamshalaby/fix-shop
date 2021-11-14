<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Visitor;
use App\UserAddress;
use App\Area;
use App\Address;
use App\Coordinate;
use App\Governorate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;


class AddressController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['getdeliveryprice' , 'getareas', 'getAllAreas', 'getGovernorates', 'getInPolygon']]);
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
         ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
           $c = !$c;
      }
      return $c;
    }

    function pointInPolygon($p, $polygon) {
        //if you operates with (hundred)thousands of points
        set_time_limit(60);
        $c = 0;
        $p1 = $polygon[0];
        $n = count($polygon);
   
        for ($i=1; $i<=$n; $i++) {
            $p2 = $polygon[$i % $n];
            if ($p->long > min($p1->long, $p2->long)
                && $p->long <= max($p1->long, $p2->long)
                && $p->lat <= max($p1->lat, $p2->lat)
                && $p1->long != $p2->long) {
                    $xinters = ($p->long - $p1->long) * ($p2->lat - $p1->lat) / ($p2->long - $p1->long) + $p1->lat;
                    if ($p1->lat == $p2->lat || $p->lat <= $xinters) {
                        $c++;
                    }
            }
            $p1 = $p2;
        }
        
        // if the number of edges we passed through is even, then it's not in the poly.
        return $c%2!=0;
    }
    public function getInPolygon(Request $request) {
        $polygon = [];
        $coordinates = Coordinate::get();
        for ($i = 0; $i < count($coordinates); $i ++) {
            $arr = (object)['long' => $coordinates[$i]->lng, 'lat' => $coordinates[$i]->lat];
            array_push($polygon, $arr);
        }
        
        $data = $this->pointInPolygon((object)['lat' => $request->lat, 'long' => $request->long], $polygon);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    public function getaddress(Request $request){
        $user = auth()->user();
        $address = UserAddress::select('id', 'latitude', 'longitude', 'extra_details')->where('user_id' , $user->id)->where('deleted', 0)->orderBy('id' , 'desc')->get()->makeHidden('main_address_id');

        for($i = 0; $i < count($address); $i++){
            if($user->main_address_id == $address[$i]['id']){
                $address[$i]['is_default'] =  true;
            }else{
                $address[$i]['is_default'] =  false;

            }
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $address , $request->lang);
        return response()->json($response , 200);
    }

    public function addaddress(Request $request){
        if ($request->lang == 'en') {
            $messages = [
                'latitude.required' => 'Latitude is required field',
                'longitude.required' => 'Longitude is required field',
                'extra_details.required' => 'Extra details is required field'
            ];
        }else {
            $messages = [
                'latitude.required' => 'Latitude حقل مطلوب',
                'longitude.required' => 'Longitude حقل مطلوب',
                'extra_details.required' => 'تفاصيل العنوان حقل مطلوب'
            ];
        }
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'extra_details' => 'required',            
        ], $messages);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first()  , null , $request->lang);
            return response()->json($response , 406);
        }
        $user_id = auth()->user()->id;
        $address = new UserAddress();
        $address->user_id = $user_id;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->extra_details = $request->extra_details;
        
        $address->save();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $address , $request->lang);
        return response()->json($response , 200);
    }

    public function removeaddress(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',           
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first()  , null , $request->lang);
            return response()->json($response , 406);
        }
        $user_id = auth()->user()->id;
        $address = UserAddress::find($request->address_id);
        if($address){
            if($address->user_id == $user_id){
                $address->update(['deleted' => 1]);

                $addresses = UserAddress::select('id', 'latitude', 'longitude', 'extra_details')->where('user_id' , $user_id)->where('deleted', 0)->get();

                $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $addresses  , $request->lang);
                return response()->json($response , 200);
            }else{
                $response = APIHelpers::createApiResponse(true , 406 , 'You do not have the authority to delete this address' , 'ليس لديك الصلاحيه لحذف هذا العنوان'  , null , $request->lang);
                return response()->json($response , 406);
            }
        }else{
            $response = APIHelpers::createApiResponse(true , 406 , 'Invalid address id' , 'رقم عنوان غير صحيح'  , null , $request->lang);
            return response()->json($response , 406);
        }

    }

    public function setmain(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',           
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $user = auth()->user();
        
        $main_address = UserAddress::where('user_id' , $user->id)->where('id' ,$request->address_id)->first();

        if(!$main_address){
            $response = APIHelpers::createApiResponse(true , 406 , 'You do not have the authority for this address' , 'ليس لديك الصلاحيه لهذا العنوان'  , null , $request->lang);
            return response()->json($response , 406);            
        }
        $user->main_address_id = $request->address_id;
        $user->save();

        $address = UserAddress::select('id', 'latitude', 'longitude', 'extra_details')->where('user_id' , $user->id)->where('deleted', 0)->orderBy('id' , 'desc')->get();

        for($i = 0; $i < count($address); $i++){
            if($user->main_address_id == $address[$i]['id']){
                $address[$i]['is_default'] =  true;
            }else{
                $address[$i]['is_default'] =  false;

            }
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $address , $request->lang);
        return response()->json($response , 200);

    }

    public function getareas(Request $request){
        $user = auth()->user();
        // dd($user->id);
        $visitor = Visitor::where('user_id', $user->id)->first();
        if (isset($visitor['id'])) {
            $address = Address::where('visitor_id', $visitor['id'])->first();

            if($request->lang == 'en'){
                $areas = Area::where('id' , $address['address_id'])->select('id', 'title_en as title')->get();
            }else{
                $areas = Area::where('id' , $address['address_id'])->select('id' , 'title_ar as title')->get();
            }
            
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $areas , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'visitor is not exist' , 'زائر غير موجود'  , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    public function getAllAreas(Request $request, Governorate $governorate){

        $areas = Area::where('governorate_id', $governorate->id)->where('deleted', 0)->select('id', 'title_' . $request->lang . ' as title')->get();
        
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $areas , $request->lang);
        return response()->json($response , 200);
        
    }

    public function getGovernorates(Request $request) {
        $governorates = Governorate::where('deleted', 0)->has('areas', '>', 0)->select('id', 'title_' . $request->lang . ' as title')->orderBy('id', 'asc')->get();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $governorates , $request->lang);
        return response()->json($response , 200);
    }

    public function getdeliveryprice(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',           
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $address = UserAddress::find($request->address_id);
        $area_id =  $address['area_id'];
        $area = Area::select('delivery_cost')->find($area_id);
        $area['delivery_cost'] = number_format((float)$area['delivery_cost'], 3, '.', '');
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $area , $request->lang);
        return response()->json($response , 200);
    }

    public function getdetails(Request $request){
        $addess_id = $request->id;
        $address = UserAddress::select('id', 'latitude', 'longitude', 'extra_details')->find($addess_id);
        
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $address , $request->lang);
        return response()->json($response , 200);
    }

    

    public function selectAddressBelongsToArea(Request $request) {
		$validator = Validator::make($request->all(), [
            'unique_id' => 'required',           
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $user = auth()->user();
        $visitor = Visitor::where('user_id', $user->id)->where('unique_id', $request->unique_id)->first();
		
        if (isset($visitor['id'])) {
            $address = Address::where('visitor_id', $visitor['id'])->first();
        
            $userAddress = UserAddress::select('id', 'latitude', 'longitude', 'extra_details')->where('user_id', $user->id)->where('deleted', 0)->where('area_id', $address['address_id'])->get();
            
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $userAddress , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'visitor is not exist' , 'زائر غير موجود'  , null , $request->lang);
            return response()->json($response , 406);
        }
        
    }


}