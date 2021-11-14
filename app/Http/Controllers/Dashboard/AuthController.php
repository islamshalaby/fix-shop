<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:dashboard', ['except' => ['login' , 'invalid']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = request(['phone', 'password']);

        if (! $token = Auth::guard('dashboard')->attempt($credentials)) {
            $response  = APIHelpers::createApiResponse(true , 401 , 'Invalid phone or password' , 'يرجي التاكد من رقم الهاتف او كلمة المرور' , null , $request->lang);
            return response()->json($response, 401);
        }

        if(! $request->fcm_token && !$request->type){
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }

        $user = Auth::guard('dashboard')->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();
        
        $token = Auth::guard('dashboard')->login($user);
        $user->token = $this->respondWithToken($token);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $user , $request->lang);
        return response()->json($response , 200);

    }

    public function invalid(Request $request){
        
        $response = APIHelpers::createApiResponse(true , 401 , 'Invalid Token' , 'تم تسجيل الخروج' , null , $request->lang);
        return response()->json($response , 401);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = Auth::guard('dashboard')->user();
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $user , $request->lang);
        return response()->json($response , 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('dashboard')->logout();
        $response = APIHelpers::createApiResponse(false , 200 , '', '' , [] , $request->lang);
        return response()->json($response , 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $responsewithtoken = $this->respondWithToken(Auth::guard('dashboard')->refresh());
        $response = APIHelpers::createApiResponse(false , 200 , '', '' , $responsewithtoken , $request->lang);
        return response()->json($response , 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('dashboard')->factory()->getTTL() * 432000
        ];
    }
}