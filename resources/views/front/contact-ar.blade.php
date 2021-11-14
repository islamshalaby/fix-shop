@extends('front.app')
@section('title', $meta->contact_title_ar)
@section('description', $meta->contact_description_ar)
@section('content')
    <main id="main">
        <!-- =======  contact ======= -->
        <section class="contact section-t8 p-top">
            <div class="container">
                <div class="row">

                    <div class="col-sm-12 section-t8">
                        <div class="row">
                            <div class="col-md-7">
                                <h3>تواصل معنا</h3>
                                
                                <form action="{{route('front.contact.store')}}" method="post" role="form" class="email-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input type="text" name="name" class="form-control form-control-lg form-control-a" placeholder="الاسم">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input name="email" type="email" class="form-control form-control-lg form-control-a" placeholder="البريد الالكترونى">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input type="text" name="phone" class="form-control form-control-lg form-control-a" placeholder="رقم الجوال" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <input type="text" name="address" class="form-control form-control-lg form-control-a" placeholder="العنوان">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="message" class="form-control" cols="45" rows="8" placeholder="الرساله" required=""></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 my-3">
                                            <div class="mb-3">
                                                <div class="loading">Loading</div>
                                                <div class="error-message"></div>
                                                <div class="sent-message">Your message has been sent. Thank you!</div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-a">ارسال</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-5 section-md-t3">
                                <div class="ContactInfo">
                                    <h5>معلومات الاتصال</h5>

                                    <div class="section-b2">
                                        <div class="icon-box">
                                            <span class="bi bi-geo-alt"></span>
                                            <p> {{$data->address_ar}}</p>
                                        </div>
                                        <div class="icon-box">
                                            <span class="bi bi-phone-vibrate"></span>
                                            <p class="">{{$data->app_phone}}</p>
                                        </div>
                                        <div class="icon-box">
                                            <span class="bi bi-envelope"></span>
                                            <a href="Mailto:{{$data->email}}" class="">{{$data->email}}</a>
                                        </div>

                                        <div class="social-box">
                                            <div class="">

                                                <h5 class="icon-title">انضم الينا</h5>
                                                
                                            </div>
                                            <div class="social-links mt-3">
                                                <a href="{{ $settings->twitter }}" class="twitter"><i class="fa fa-twitter"></i></a>
                                                <a href="{{ $settings->facebook }}" class="facebook"><i class="fa fa-facebook"></i></a>
                                                <a href="{{ $settings->instegram }}" class="instagram"><i class="fa fa-instagram"></i></a>
{{--                                                <a href="#" class="skype"><i class="fa fa-skype"></i></a>--}}
{{--                                                <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>--}}
{{--                                                <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>--}}
                                                <a href="{{ $settings->youtube }}" class="youtube"><i class="fa fa-youtube-play"></i></a>
                                            </div>
                                        </div>



                                    </div>
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
