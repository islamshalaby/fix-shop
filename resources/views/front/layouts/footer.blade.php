<!-- ======= Footer ======= -->
    <footer id="footer">


        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 ">
                        <h4>تواصل معنا</h4>
                        <div class="footer-MailLinks">
                            <p>
                                <i class="fa fa-phone"></i> {{ $settings->phone }} </p>
                            <a target="_blank" href="mailto:{{ $settings->email }}"> <i class="fa fa-envelope-open"></i> {{ $settings->email }}</a>
                        </div>

                        <h5>تابعنا على</h5>
                        <div class="social-links mt-3">
                            @if (!empty($settings->twitter))
                            <a target="_blank" href="{{ $settings->twitter }}" class="twitter"><i class="fa fa-twitter"></i></a>
                            @endif
                            @if (!empty($settings->facebook))
                            <a target="_blank" href="{{ $settings->facebook }}" class="facebook"><i class="fa fa-facebook"></i></a>
                            @endif
                            @if (!empty($settings->instegram))
                            <a target="_blank" href="{{ $settings->instegram }}" class="instagram"><i class="fa fa-instagram"></i></a>
                            @endif
                            @if (!empty($settings->instegram))
                            <a target="_blank" href="{{ $settings->youtube }}" class="youtube"><i class="fa fa-youtube-play"></i></a>
                            @endif
                            {{--  <a href="{{ $settings->youtube }}" class="skype"><i class="fa fa-skype"></i></a>
                            <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                            <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>  --}}
                        </div>
                    </div>


                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>معلومات</h4>
                        <ul>
                            {{--  <li><i class="bx bx-chevron-right"></i> <a href="#">الاسئله الشائعه</a></li>

                            <li><i class="bx bx-chevron-right"></i> <a href="#">الخدمات</a></li>  --}}
                            <li><i class="bx bx-chevron-right"></i> <a target="_blank" href="{{ route('terms', 'ar') }}">الشروط والاحكام</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a target="_blank" href="{{ route('privacy', 'ar') }}">سياسة الخصوصية</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>القائمه الرائيسيه</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="/">الرئيسية</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{route('front.about_ar')}}">من نحن</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{route('front.offers')}}">العروض</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{route('front.contact_ar')}}">تواصل معنا</a></li>

                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-contact">
                        <h4>يمكنك تحميل التطبيق من خلال :<span>.</span></h4>
                        <div class="d-flex dowFooter">
                            <a href="#"><img src="/front/assets/img/dow2.png" alt="" /></a>
                            <a href="#"><img src="/front/assets/img/dow.png" alt="" /></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="copyright-footer">
            <div class="container">
                <p class="copyright color-text-a">
                    © جمبع الحقوق محفوظه الى
                    <span class="color-a"> U-Smart</span> .
                </p>
                <div class="credits">

                    Designed by <a href="https://u-smart.co/">U-Smart</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- End  Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/front/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="/front/assets/lib/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="/front/assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    @if (Auth::guard('user')->user())
    <!-- favorite button submit -->
    <script>
        $('.like, .AddCart').on('click', function(e) {
            e.preventDefault()
            $(this).parent('form').submit()
        })
        $(".add-det").on('click', function (e) {
            e.preventDefault()
            $(this).parent('div').parent('form').submit()
        })
    </script>
    @endif
    <!-- add to cart button submit | remove from cart button submit -->
    <script>
        $('.AddCart:not(.addbtn), .remov-cart, .delete-item').on('click', function(e) {
            e.preventDefault()
            $(this).parent('form').submit()
        })
    </script>

    <!-- inrement | decrment -->
    <script>
        $(".inc").on("click", function() {
            var inputVal = $(this).prev('input').attr('value')

            $(this).prev('input').attr('value', Number(inputVal) + 1)
        })
        $(".dec").on("click", function() {
            var inputVal = $(this).next('input').attr('value')
            if (inputVal > 1) {
                $(this).next('input').attr('value', Number(inputVal) - 1)
            }
            
        })

        // search submit
        $(".search-submit").on("click", function() {
            $(this).parent('.input-group-btn').parent('.input-group').parent('form').submit()
        })
    </script>
    @stack('scripts')
    @include('sweetalert::alert')
</body>

</html>
