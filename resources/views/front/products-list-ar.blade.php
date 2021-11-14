@extends('front.app')
@section('title', $meta->products_title_ar)
@section('description', $meta->products_description_ar)

@section('content')
    <main id="main">

        <!-- =======  products-list ======= -->
        <section class="products-list section-t8 ">
            <div class="container p-0">
                @if(count($web_image) > 0)
                <div class="bannerTop">
                    <a target="_blank" href="{{ $web_image[0]->type == 2 ? $web_image[0]->content : route('front.product_details_ar', $web_image[0]->content) }}">
                        <img src="https://res.cloudinary.com/al-thuraya/image/upload/q_100/v1581928924/{{$web_image[0]->image}}" alt="">
                    </a>
                </div>
               @endif
                <div class="title-wrap d-flex justify-content-between">
                    <div class="title-box">
                        <h2 class="title-a"> المنتجات</h2>
                    </div>
                </div>
                <div class="row">
                    @if (count($data) > 0)
                        @foreach($data as $row)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card productList">
                                <div class="view view-sixth card-img-top">
                                    @if (Auth::guard('user')->user())
                                    <form action="{{ route('front.like.product.put') }}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="PUT">
                                        <input type="hidden" name="product_id" value="{{ $row->id }}" />
                                        <a href="#" class="favorite-pr like {{ $row->favorite == true ? 'Active' : '' }}"><i></i></a>
                                    </form>
                                    @else
                                    <a href="{{ route('front.login') }}" class="favorite-pr {{ $row->favorite == true ? 'Active' : '' }}"><i></i></a>
                                    @endif
                                    <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_245,h_245,q_100/v1581928924/{{ $row->main_image }}">
                                    <div class="mask ">
                                        <div class="actionBut">
                                            <form action="{{ route('front.add.cart.put') }}" method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="PUT">
                                                <input type="hidden" name="product_id" value="{{ $row->id }}"  />
                                                <input type="hidden" name="count" value="1"  />
                                                <a href="#" class="AddCart info">اضف الى السله</a>
                                            </form>
                                            <a href="{{route('front.product_details_ar',$row->id)}}" class="info viewProduct">عرض المنتج</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><a href="{{route('front.product_details_ar',$row->id)}}">{{$row->title}}</a></p>
                                    @if($row->offer == 1)
                                    <div class="PriceDiscount">
                                        <div class="PriceBox">
                                            <span>{{$row->price_before_offer}} {{ $currency_data['currency']->currency_ar }}</span> {{$row->final_price}} {{ $currency_data['currency']->currency_ar }}
                                        </div>
                                        <div class="DiscountBox">
                                            {{$row->offer_percentage}}%
                                        </div>
                                    </div>
                                    @else
                                        <div class="PriceDiscount">
                                            <div class="PriceBox">
                                                {{$row->price_before_offer}} {{ $currency_data['currency']->currency_ar }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @else
                    <div class="d-flex">
                        <div class="mx-auto justify-content-center">
                            <p>لا يوجد منتجات</p>
                        </div>
                    </div>
                    @endif
                    
                </div>
                <div class="d-flex">
                    <div class="mx-auto justify-content-center">
                        {{$data->links()}}
                    </div>
                </div>
            </div>
            
        </section>
        <!-- End products Section -->
        

    </main>
    <!-- End #main -->
@endsection
