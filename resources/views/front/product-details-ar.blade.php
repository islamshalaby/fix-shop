@extends('front.app')
@section('title', $data->title)
@section('content')
    <main id="main">
        <!-- =======  product-details ======= -->
        <section class="product-details section-t8">
            <div class="container p-0">
                <div class="productTopBox">
                    <div class="row">
                        <div class="col-lg-3 col-md-4  col-12">
                            <div class="view view-sixth PhotoBox">
                                @if (Auth::guard('user')->user())
                                <form action="{{ route('front.like.product.put') }}" method="post">
                                    @csrf
                                    <input name="_method" type="hidden" value="PUT">
                                    <input type="hidden" name="product_id" value="{{ $data->id }}" />
                                    <a href="#" class="favorite-pr like {{ $data->favorite == true ? 'Active' : '' }}"><i></i></a>
                                </form>
                                @else
                                <a href="{{ route('front.login') }}" class="favorite-pr {{ $data->favorite == true ? 'Active' : '' }}"><i></i></a>
                                @endif
                                <img src="https://res.cloudinary.com/al-thuraya/image/upload/w_260,q_100/v1581928924/{{ $data->images[0] }}" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12">
                            <h2 class="p-title">{{$data->title}}</h2>
                            @if($data->offer == 1)
                                <h3 class="Discount">
                                    <font>{{$data->price_before_offer}} {{ $currency_data['currency']->currency_ar }}</font> <span class="DiscountBox">{{$data->offer_percentage}}%</span>
                                </h3>
                                <h3 class="p-price">{{$data->final_price}} {{ $currency_data['currency']->currency_ar }}</h3>
                            @else
                                <h3 class="p-price">{{$data->price_before_offer}} {{ $currency_data['currency']->currency_ar }}</h3>
                            @endif
                            <form action="{{ route('front.add.cart.put') }}" method="post">
                                @csrf
                                <input name="_method" type="hidden" value="PUT">
                                <input type="hidden" name="product_id" value="{{ $data->id }}"  />
                                <div class="quantity">
                                    <p>الكمية</p>
                                    <div class="pro-qty">
                                        <span class="dec qtybtn">-</span>
                                        <input type="text" name="count" value="1">
                                        <span class="inc qtybtn">+</span>
                                    </div>
                                    <button type="submit" class="btn AddCart add-det addbtn">اضف الى السله</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="ProductDetailsBox">
                    <div class="title-wrap d-flex justify-content-between">
                        <div class="title-box">
                            <h2 class="title-a"> عن المنتج</h2>
                        </div>

                    </div>
                    <p>{{$data->description}}</p>
                </div>
                <div class="section-Offers ">
                    <div class="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="title-wrap d-flex justify-content-between">
                                    <div class="title-box">
                                        <h2 class="title-a">احدث العروض</h2>
                                    </div>
                                    <div class="title-link">
                                        <a href="{{route('front.offers')}}">جمبع العروض
                                        <span class="bi bi-chevron-right"></span>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="offer-carousel" class="swiper-container">
                            <div class="swiper-wrapper">
                                <!-- End carousel item -->
                                @if (count($recent_offers) > 0)
                                    @foreach ($recent_offers as $offer)
                                        <div class="carousel-item-c swiper-slide">
                                            <div class="card productList">
                                                <div class="view view-sixth card-img-top">
                                                    @if (Auth::guard('user')->user())
                                                    <form action="{{ route('front.like.product.put') }}" method="post">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="PUT">
                                                        <input type="hidden" name="product_id" value="{{ $offer->id }}" />
                                                        <a href="#" class="favorite-pr like {{ $offer->favorite == true ? 'Active' : '' }}"><i></i></a>
                                                    </form>
                                                    @else
                                                    <a href="{{ route('front.login') }}" class="favorite-pr {{ $offer->favorite == true ? 'Active' : '' }}"><i></i></a>
                                                    @endif
                                                    <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_245,h_245,q_100/v1581928924/{{ $offer->main_image }}">
                                                    <div class="mask ">
                                                        <div class="actionBut">
                                                            <form action="{{ route('front.add.cart.put') }}" method="post">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="PUT">
                                                                <input type="hidden" name="product_id" value="{{ $offer->id }}"  />
                                                                <input type="hidden" name="count" value="1"  />
                                                                <a href="#" class="AddCart info">اضف الى السله</a>
                                                            </form>
                                                            <a href="{{route('front.product_details_ar',$offer->id)}}" class="info viewProduct">عرض المنتج</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p><a href="{{route('front.product_details_ar',$offer->id)}}">{{ $offer->title }}</a></p>
                                                    <div class="PriceDiscount">
                                                        <div class="PriceBox">
                                                            @if ($offer->offer > 0)
                                                                <span>{{ $offer->price_before_offer }} {{ $currency_data['currency']->currency_ar }}</span>
                                                            @endif
                                                            {{ $offer->final_price }} {{ $currency_data['currency']->currency_ar }}
                                                        </div>
                                                        @if ($offer->offer > 0)
                                                            <div class="DiscountBox">
                                                                {{ $offer->offer_percentage }} %
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End carousel item -->
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="offer-carousel-pagination carousel-pagination"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- End #main -->
@endsection
