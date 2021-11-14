@extends('front.app')
@section('title', 'إنشاء حساب')
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
                            <li>تسجيل الدخول </li>

                        </ul>
                    </div>
                    <h3 class="">تسجيل الدخول</h3>
                    <div class="row">
                        <div class="col-md-7">


                            <form action="{{ route('front.register.post') }}" method="post">
                                <h5>عميل جديد ؟</h5>
                                <p>
                                    نرجو الدخول إذا كان لديك حساب
                                </p>
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>الإسم</label>
                                            <input name="name" type="text"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="ادخل الإسم" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>البريد الالكترونى</label>
                                            <input name="email" type="email"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="ادخل البريد الالكترونى" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-6 mb-12 mb-3">
                                        <div style="direction: ltr;" class="form-group">
                                            {{-- <label>الجوال</label> --}}
                                            <input id="phoneNumber" type="tel" name="phone"
                                                class="form-control form-control-lg form-control-a"
                                                 required="">
                                                <span style="display: none" id="valid-msg" class="hide">✓ Valid</span>
										        <span style="display: none" id="error-msg" class="hide">Invalid number</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-12 mb-3">
                                        <div class="form-group">
                                            <label>كلمه المرور</label>
                                            <input type="password" name="password"
                                                class="form-control form-control-lg form-control-a"
                                                placeholder="سجل كلمه المرور" required="">
                                        </div>
                                    </div>
                                    <div class="mb-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" checked type="checkbox"
                                                id="flexSwitchCheckDefault"> <label class="form-check-label"
                                                for="flexSwitchCheckDefault">
                                                <p>
                                                    أوافق على  <a href="{{ route('terms', 'ar') }}">الشروط والأحكام</a>
                                                </p>
                                            </label>


                                        </div>
                                    </div>
                                    <div class="mb-12 d-flex forgotPasswordLink">
                                        <button type="submit" disabled class="btn btn-a btn-orange register-btn">سجل الان</button>
                                        <a href="{{ route('front.forget.password') }}">نسيت رقمك السري ?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-5 section-md-t3">

                            <div class="LoginBox">
                                <h5>عميل بالفعل؟</h5>
                                <p>برجاء الضغط هنا لتسجيل الدخول</p>
                                <a href="{{ route('front.login') }}" class="btn btn-a btn-orange">سجل الدخول</a>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>
<script>
    
    $("input[name='phone'], input[name='email'], input[name='password']").on('keyup', function() {
        var phone = $("input[name='phone']").val(),
        email = $("input[name='email']").val(),
        pass = $("input[name='password']").val()
        
        if ($("#flexSwitchCheckDefault").is(":checked") && phone.length > 0 && email.length > 0 && pass.length > 0) {
            $(".register-btn").prop('disabled', false)
        }else {
            $(".register-btn").prop('disabled', true)
        }
    })
    

    $("#flexSwitchCheckDefault").on("change", function() {
        var phone = $("input[name='phone']").val(),
            email = $("input[name='email']").val(),
            pass = $("input[name='password']").val()
        if ($(this).is(":checked") && phone.length > 0 && email.length > 0 && pass.length > 0) {
            $(".register-btn").prop('disabled', false)
        }else {
            $(".register-btn").prop('disabled', true)
        }
    })

    // show country code
    var telInput = document.querySelector("#phoneNumber"),
    errorMsg = $("#error-msg"),
	validMsg = $("#valid-msg");
	  
	  {{--  window.intlTelInput(telInput, {
		hiddenInput: "full_phone",
		utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js?1603274336113" // just for formatting/placeholders etc
	  });  --}}
	  var iti = window.intlTelInput(telInput, {
		initialCountry: "auto",
		nationalMode: true,
		geoIpLookup: function(callback) {
		  $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "us";
			callback(countryCode);
		  });
		},
		utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js?1603274336113" // just for formatting/placeholders etc
	  });
	  
	  var output = document.querySelector("#output")
	  var handleChange = function() {
		var text = (iti.isValidNumber()) ? "International: " + iti.getNumber() : "Please enter a number below";
		var textNode = document.createTextNode(text);
		
		$("#phoneNumber").attr("value", iti.getNumber())
		$("#phoneNumber").val(iti.getNumber())
		
	  };
	  
	  // listen to "keyup", but also "change" to update when the user selects a country
	  telInput.addEventListener('change', handleChange);
	  telInput.addEventListener('keyup', handleChange);
</script>

@endpush
