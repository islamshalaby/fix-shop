@extends('front.app')
@section('title', 'الملف الشخصى')
@section('content')
<main id="main">

    <!-- =======  contact ======= -->
    <section class="Ac-Login section-t8 p-top">
        <div class="container">
            <div class="row">


                <div class="col-sm-12 section-t8">
                    <div class="breadCrumb breadCrumbinner">
                        <ul>
                            <li>
                                <a href="/"><i class="fa fa-home">الرئيسية </i></a>
                                -
                            </li>
                            <li>الملف الشخصى</li>

                        </ul>
                    </div>
                    <h3 class="">الملف الشخصى</h3>
                    <div class="row">
                        <div class="col-md-7">


                            <form action="" method="post">
                                {{--  <h5>تحديث البيانات الشخصية</h5>  --}}
                                
                                {{ csrf_field() }}
                                <input type="hidden" value="PUT" name="_method" />
                                <div class="row">
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>الإسم</label>
                                            <input name="name" type="text"
                                                class="form-control form-control-lg form-control-a"
                                                value="{{ auth()->guard('user')->user()->name }}" placeholder="ادخل الإسم" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>البريد الالكترونى</label>
                                            <input name="email" type="email"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="ادخل البريد الالكترونى" value="{{ auth()->guard('user')->user()->email }}" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>رقم الجوال</label>
                                            <input id="phoneNumber" type="tel" name="phone"
                                            value="{{ auth()->guard('user')->user()->phone }}" class="form-control form-control-lg form-control-a"
                                                 required="">
                                        </div>
                                        
                                    </div>

                                    <div class="mb-12 d-flex forgotPasswordLink">
                                        <button type="submit" class="btn btn-a btn-orange register-btn">تحديث</button>
                                    </div>
                                </div>
                            </form>
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
