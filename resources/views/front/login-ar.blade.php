@extends('front.app')
@section('title', 'تسجيل دخول')
@section('content')
<main id="main">

    <!-- =======  contact ======= -->
    <section class="Ac-Login section-t8 p-top">
        <div class="container">
            <div class="row">


                <div class="col-sm-12 section-t8">

                    <div class="row">
                        <div class="col-md-7">
                            <div class="breadCrumb breadCrumbinner">
                                <ul>
                                    <li>
                                        <a href="index.html"><i class="fa fa-home">الرئيسية </i></a>
                                        -
                                    </li>
                                    <li>تسجيل الدخول </li>

                                </ul>
                            </div>
                            <h3 class="">تسجيل الدخول</h3>

                            <form action="" method="post" role="form" class="email-form LoginBox">
                                <h5>عميل بالفعل؟</h5>
                                <p>
                                    نرجو الدخول إذا كان لديك حساب
                                </p>
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>البريد الالكترونى</label>
                                            <input name="email" type="email"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="ادخل البريد الالكترونى" required="">
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>كلمه المرور</label>
                                            <input type="password" name="password"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="سجل كلمه المرور" required="">
                                        </div>
                                    </div>
                                    {{-- <div class="mb-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                id="flexSwitchCheckDefault"> <label class="form-check-label"
                                                for="flexSwitchCheckDefault">
                                                <p>
                                                    أوافق على  <a href="#">الشروط والأحكام</a>
                                                </p>
                                            </label>


                                        </div>
                                    </div> --}}
                                    <div class="mb-12 d-flex forgotPasswordLink">
                                        <button type="submit" class="btn btn-a btn-orange">سجل الدخول</button>
                                        <a href="{{ route('front.forget.password') }}">نسيت رقمك السري ?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-5 section-md-t3">
                            <div class="socialLogin">
                                <a href="{{ url('auth/google') }}" class="GoogleLogin"><i class="bi bi-google"></i>تسجيل الدخول عن طريق جوجلI </a>
                                <a href="{{url('/redirect')}}" class="FacebookLogin"><i class="bi bi-facebook"></i>تسجيل الدخول عن طريق فيسبوك </a>
                            </div>

                            <div class="LoginBox">
                                <h5>عميل جديد ؟</h5>
                                <p>برجاء الضغط هنا لإنشاء حساب</p>
                                      <a href="{{ route('front.register') }}"  class="btn btn-a btn-orange">حساب جديد</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End about Section -->


</main>
<!-- End #main -->
@endsection
