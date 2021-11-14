<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\User;
use App\Visitor;
use App\Country;
use App\Cart;
use App\WebVisitor;
use App\Setting;
use App\Product;
use App\ProductVip;
use App\Favorite;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller{
    protected $webVisitor;
    protected $currency;
    protected $cart;
    protected $totalAdded;
    protected $all_currency_data;

    

    // generate unique id
    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    // get register
    public function register(Request $request) {
        Parent::getCartData($request);
        return view('front.register-ar');
    }

    // post register
    public function postRegister(Request $request) {
        $messages = [
            'phone.required' => 'رقم الهاتف حقل مطلوب',
            'phone.unique' => 'رقم الهاتف موجود بالفعل',
            'email.required' => 'البريد الإلكترونى حقل مطلوب',
            'email.unique' => 'بريد إلكترونى موجود بالفعل',
            'password.required' => 'كلمة المرور جقل مطلوب',
            'name.required' => 'الإسم حقل مطلوب'
        ];
        $data = $this->validate(\request(),
        [
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'name' => 'required'
        ], $messages);
        $data['password'] = Hash::make($request->password);
        $data['remember_token'] = $this->unique_code(9);
        $data['active'] = 0;
        $user = User::create($data);
        $ip = Parent::getIp($request);
        $visitor = WebVisitor::where('ip', $ip)->first();

        if ($visitor) {
            $visitor->update(['user_id' => $user->id]);
        }
        $data['user'] = $user;
        Mail::send('confirm_email', $data, function($message) use ($user) {
            $message->to($user->email, $user->name)->subject
                ('Account Activation');
            $message->from('modaapp9@gmail.com','Al thuraya');
        });

        Alert::success('حساب جديد', 'لتفعيل حسابك اضغط على الرابط الذى تم إرساله على بريدك الإلكترونى');

        return redirect()->route('front.home');
    }

    // activate account
    public function activateAccount(Request $request) {
        Parent::getCartData($request);
        $user = User::where('remember_token', $request->token)->first();

        if ($user && $user->active == 0) {
            $user->active = 1;
            $user->save();

            Auth::guard('user')->login($user);
            Alert::success('حساب جديد', 'تم تفعيل حسابك بنجاح');
        }else {
            Alert::error('دخول خاطئ', 'تم تفعيل هذا الحساب من قبل');
        }

        return redirect()->route('front.home');
    }

    // logout
    public function logout() {
        Auth::guard('user')->logout();
  
        return redirect()->route('front.home');
    }

    // get login
    public function getLogin(Request $request) {
        Parent::getCartData($request);
        return view('front.login-ar');
    }

    // post login
    public function login(Request $request) {
        $messages = [
            'email.required' => 'البريد الإلكترونى حقل مطلوب',
            'email.email' => 'بريد إلكترونى غير صحيح',
            'password.required' => 'كلمة المرور حقل مطلوب'
        ];
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);

        
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1])) {
            $ip = Parent::getIp($request);
            $visitor = WebVisitor::where('ip', $ip)->first();
            if ($visitor) {
                $visitor->update(['user_id' => Auth::guard('user')->user()->id]);
            }
            return redirect('/');
        }
        Alert::error('تسجيل دخول خاطئ', 'البريد الإلكترونى أو كلمة المرور غير صحيحة أو حسابك غير مفعل');
        return redirect('/login');
    }

    // get change password
    public function getChangePassword(Request $request) {
        Parent::getCartData($request);
        return view('front.change-password-ar');
    }

    // update password
    public function updatePass(Request $request) {
        $data = $this->validate(\request(),
        [
            'password' => 'required',
            'oldpassword' => 'required'
        ]);
        
        $user = auth()->guard('user')->user();
        // dd(password_verify($request->oldpassword, $user->password));
        if (password_verify($request->oldpassword, $user->password)) {
            $user->update(['password' => Hash::make($request->password)]);
        
            Alert::success('تحديث كلمة المرور', 'تم تحديث كلمة المرور بنجاح');
            
            return redirect('/');
        }else {
            Alert::error('تحديث كلمة المرور', 'كلمة مرور غير صحيحة');
            
            return redirect()->back();
        }
    }

    // forget password
    public function forgetPassword(Request $request) {
        Parent::getCartData($request);
        return view('front.forgot-password-ar');
    }

    // reset password
    public function resetPassword(Request $request) {
        $this->validate(\request(),
        [
            'email' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $random_pass = Str::random(8);
            $user->update(['remember_token' => $random_pass]);
            $data['random_pass'] = $random_pass;
            $data['user'] = $user;

            Mail::send('reset_pass', $data, function($message) use ($user) {
                $message->to($user->email, $user->name)->subject
                    ('Reset password');
                $message->from('modaapp9@gmail.com','Al thuraya');
            });

            Alert::success('إستعادة كلمة المرور', 'تم إرسال كود على بريدك الإلكترونى');
            
            return redirect()->route('front.verify.code');
        }else {
            Alert::error('إستعادة كلمة المرور', 'بريد إلكترونى خاطئ');
            
            return redirect()->back();
        }
    }

    // get verify code
    public function getVerifyCode(Request $request) {
        Parent::getCartData($request);
        return view('front.verify_code');
    }

    // verify code
    public function verifyCode(Request $request) {
        $this->validate(\request(),
        [
            'code' => 'required'
        ]);

        $user = User::where('remember_token', $request->code)->first();
        if ($user) {
            $user->update(['remember_token' => '']);

            Auth::guard('user')->login($user);
            return redirect()->route('front.reset.password');
        }else {
            Alert::error('إستعادة كلمة المرور', 'كود خاطئ');
            
            return redirect()->back();
        }
    }

    // get reset password
    public function getResetPassword(Request $request) {
        Parent::getCartData($request);
        return view('front.reset_pass');
    }

    // reset password
    public function updatePassword(Request $request) {
        $this->validate(\request(),
        [
            'password' => 'required'
        ]);

        $user = auth()->guard('user')->user();
        $user->update(['password' => Hash::make($request->password)]);

        Alert::success('إستعادة كلمة المرور', 'تم تحديث كلمة المرور بنجاح');
            
        return redirect('/');
    }

    // get profile
    public function getProfile(Request $request) {
        Parent::getCartData($request);
        return view('front.profile');
    }

    // update profile
    public function updateProfile(Request $request) {
        $user = auth()->guard('user')->user();
        $messages = [
            'phone.required' => 'رقم الهاتف حقل مطلوب',
            'phone.unique' => 'رقم الهاتف موجود بالفعل',
            'email.required' => 'البريد الإلكترونى حقل مطلوب',
            'email.unique' => 'بريد إلكترونى موجود بالفعل',
            'name.required' => 'الإسم حقل مطلوب'
        ];
        $data = $this->validate(\request(),
        [
            'phone' => 'required|unique:users,phone,' . $user->id,
            'email' => 'required|unique:users,email,' . $user->id,
            'name' => 'required'
        ], $messages);

        $user->update($data);

        Alert::success('الملف الشخصى', 'تم تحديث بياناتك الشخصية');
        
        return redirect('/');
    }
}