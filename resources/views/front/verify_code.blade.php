@extends('front.app')
@section('title', 'كود إستعادة كلمة المرور')
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
                                <a href="index.html"><i class="fa fa-home">الرئيسية </i></a>
                                -
                            </li>
                            <li>تسجيل الدخول </li>

                        </ul>
                    </div>
                    <h3 class="">تسجيل الدخول</h3>
                    <div class="row">
                        <div class="col-md-7">


                            <form action="{{ route('front.verify.code.put') }}" method="post" role="form" class="email-form LoginBox">
                            <p>
                                كود لاعاده التعيين                                </p>
                                @csrf
                                <input type="hidden" name="_method" value="PUT" />
                                <div class="row">
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>الكود</label>
                                            <input name="code" type="text"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="ادخل الكود المرسل للبريد الإلكترونى" required="">
                                        </div>
                                    </div>

                                    <div class="mb-12 d-flex forgotPasswordLink">
                                        <button type="submit" class="btn btn-a btn-orange">ارسال</button>
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