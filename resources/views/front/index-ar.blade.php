@extends('front.app')
@section('title', $meta->home_title_ar)
@section('description', $meta->home_description_ar)

@section('content')
<!-- ======= slider Section ======= -->
    <div class="intro intro-carousel swiper-container container">

        <div class="swiper-wrapper">

            @if (count($data['top_sliders']) > 0)
            @foreach ($data['top_sliders'] as $slider)
            <div class="swiper-slide carousel-item-a intro-item bg-image" style="background-image: url(https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/q_100/v1581928924/{{ $slider->ad ? $slider->ad->image : '' }})">
                <div class="overlay overlay-a"></div>
                <div class="intro-content display-table">
                    <div class="table-cell">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="intro-body">
                                        <p class="intro-title-top">
                                            {{ $slider->text1_ar }}
                                        </p>
                                        <h1 class="intro-title mb-4 ">
                                            <span class="color-b">{{ $slider->highlighted }} </span> {{ $slider->text2_ar }}
                                            <br>{{ $slider->text3_ar }}
                                        </h1>
                                        <p class="intro-subtitle intro-price">
                                            <a target="_blank" href="{{ $slider->ad->type == 2 ? $slider->ad->content : route('front.product_details_ar', [$slider->ad->content]) }}"><span class="price-a">{{ $slider->price }}</span></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!-- End slider Section -->

    <main id="main">

        <!-- ======= Categories Section ======= -->
        <section class="section-services section-t8">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title-wrap d-flex justify-content-between">
                            <div class="title-box">
                                <h2 class="title-a">الفئـــات</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if (count($data['categories']) > 0)
                    @foreach ($data['categories'] as $cat)
                    <div class="col-lg-3 col-md-3 col-6">
                        <a href="{{ $cat->url }}">
                            <div class="view view-sixth">
                                
                                <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_245,h_245,q_100/v1581928924/{{ $cat->image }}">
                                
                                <div class="mask">
                                    <h2>{{ $cat->title }}</h2>
                                    <a href="{{ $cat->url }}" class="info"> المزيد</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </section>
        <!-- End Services Section -->

        <!-- ======= Latest Products Section ======= -->

        <section class="section-products section-t8">
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <div class="title-wrap d-flex justify-content-between">
                            <div class="title-box">
                                <h2 class="title-a">احدث المنتجات</h2>
                            </div>
                            <div class="title-link">
                                <a href="{{route('front.products_ar')}}">جميع المنتجات
                                    <span class="bi bi-chevron-left"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="products-carousel" class="swiper-container">
                    <div class="swiper-wrapper">
                        @if (count($data['recent_product']) > 0)
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($data['recent_product'] as $item)
                        <div class="carousel-item-b swiper-slide">
                            <div class="card productList">
                                
                                <div class="view view-sixth card-img-top">
                                    @if (Auth::guard('user')->user())
                                    <form action="{{ route('front.like.product.put') }}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="PUT">
                                        <input type="hidden" name="product_id" value="{{ $item->id }}" />
                                        <a href="#" class="favorite-pr like {{ $item->favorite == true ? 'Active' : '' }}"><i></i></a>
                                    </form>
                                    @else
                                    <a href="{{ route('front.login') }}" class="favorite-pr {{ $item->favorite == true ? 'Active' : '' }}"><i></i></a>
                                    @endif

                                    <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_245,h_245,q_100/v1581928924/{{ $item->main_image }}">
                                    <div class="mask ">
                                        <div class="actionBut">
                                            <form action="{{ route('front.add.cart.put') }}" method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="PUT">
                                                <input type="hidden" name="product_id" value="{{ $item->id }}"  />
                                                <input type="hidden" name="count" value="1"  />
                                                <a href="#" class="AddCart info">اضف الى السله</a>
                                            </form>
                                            <a href="{{route('front.product_details_ar',$item->id)}}" class="info viewProduct">عرض المنتج</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><a href="{{route('front.product_details_ar',$item->id)}}">{{ $item->title }}</a></p>
                                    <div class="PriceDiscount">
                                        <div class="PriceBox">
                                            @if ($item->offer > 0)
                                            <span>{{ $item->price_before_offer }} {{ $data['currency']->currency_ar }}</span>
                                            @endif
                                            {{ $item->final_price }} {{ $data['currency']->currency_ar }}
                                        </div>
                                        @if ($item->offer > 0)
                                        <div class="DiscountBox">
                                            {{ $item->offer_percentage }} %
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End carousel item -->
                        @php
                            $i ++;
                        @endphp
                        @endforeach
                        @endif

                    </div>
                </div>
                <div class="products-carousel-pagination carousel-pagination"></div>

            </div>
        </section>
        <!-- End Latest المنتجات Section -->

        <!-- ======= Banner Section ======= -->
        <section class="section-agents section-t8">
            <div class="container">
                <div class="BannerHome">
                    <a target="_blank" href="
                    @if(count($data['ads']) > 0)
                    {{ $data['ads'][0]->type == 2 ? $data['ads'][0]->content : route('front.product_details_ar', $data['ads'][0]->content) }}
                    @endif
                    ">
                    
                        <img src="{{ count($data['ads']) > 0 ? 'https://res.cloudinary.com/' . cloudinary_app_name() . '/image/upload/q_100/v1581928924/' . $data['ads'][0]['image'] : '' }}" >
                   
                    </a>
                </div>
            </div>
        </section>
        <!-- End Banner Section -->

        <!-- ======= Latest Offers Section ======= -->
        <section class="section-Offers section-t8">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title-wrap d-flex justify-content-between">
                            <div class="title-box">
                                <h2 class="title-a">احدث العروض</h2>
                            </div>
                            <div class="title-link">
                                <a href="{{route('front.offers')}}">جمبع العروض
                                    <span class="bi bi-chevron-left"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="offer-carousel" class="swiper-container">
                    <div class="swiper-wrapper">
                        @if (count($data['recent_offers']) > 0)
                        @foreach ($data['recent_offers'] as $offer)
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
                                            <span>{{ $offer->price_before_offer }} {{ $data['currency']->currency_ar }}</span>
                                            @endif
                                            {{ $offer->final_price }} {{ $data['currency']->currency_ar }}
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
        </section>
        <!-- End Latest Offers Section -->


    </main>
    <!-- End #main -->
@endsection
