@extends('front.app')
@section('title', 'تغيير كلمة المرور')
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
                            <li>تغيير كلمة المرور </li>

                        </ul>
                    </div>
                    <h3 class="">تغيير كلمة المرور</h3>
                    <div class="row">
                        <div class="col-md-7">


                            <form action="{{ route('front.reset.password.put') }}" method="post" role="form" class="email-form LoginBox">
                                @csrf
                                <input type="hidden" name="_method" value="PUT" />
                                <div class="row">
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>كلمة المرور الجديدة</label>
                                            <input type="password" name="password"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="سجل كلمه المرور" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>كرر كلمة المرور الجديدة</label>
                                            <input type="password"
                                            class="form-control form-control-lg form-control-a repeat-pass"
                                            placeholder="سجل كلمه المرور" required="">
                                        </div>
                                    </div>

                                    <div class="mb-12 d-flex forgotPasswordLink">
                                        <button disabled type="submit" class="btn btn-a btn-orange change-submit">ارسال</button>
                                        <a href="{{ route('front.forget.password') }}">نسيت رقمك السري ?</a>
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

@push('scripts')
    <script>
        $(".repeat-pass").on('keyup', function() {
            var repeatPass = $(this).val(),
                pass = $('input[name="password"]').val()
                if (repeatPass != pass) {
                    $(".change-submit").prop('disabled', true)
                    if ($(".pass-required").length) {

                    }else {
                        $(this).parent('.form-group').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 pass-required" role="alert"> كلمة المرور يجب أن تكون متطابقة</div>
                        `)
                    }
                    
                }else {
                    $(".change-submit").prop('disabled', false)
                    $(".pass-required").remove()
                }
        })
    </script>
@endpush