@extends('front.app')
@section('title', $meta->cart_title_ar)
@section('description', $meta->cart_description_ar)
@section('content')
<main id="main">

    <!-- =======  cart ======= -->
    <section class="OrderDetails section-t8 p-top">
        <div class="container">

            <div class="boxCartHead d-flex justify-content-between">
                <div class="boxIconCart Active">
                    <a href="{{ route('front.cart') }}">
                        <i class="bi bi-basket"></i> العربه
                    </a>
                </div>
                <div class="LineBoxCart Active"></div>
                <div class="boxIconCart Active">
                    <a href="{{ auth()->guard('user')->user() ? route('front.cart.payment') : route('front.login') }}">
                        <i class="bi bi-credit-card-2-front"></i>الدفع
                    </a>
                </div>
                <div class="LineBoxCart "></div>
                <div class="boxIconCart ">
                    <a href="">
                        <i class="bi bi-journal-check"></i> تأكيد الطلب
                    </a>
                </div>
            </div>

            <div class="cart-contan">
                <div id="cart-details">
                    <div class="row" id="det">
                        <div class="col-lg-8 col-md-7 col-12">
                            <div class="card p-2">
                                @if (count($carts) > 0)
                                @foreach ($carts as $item)
                                <div class="DetailsBox">
                                    <div class="imgCart">
                                        <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_144,h_209,q_100/v1581928924/{{ $item->main_image }}" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="textCart">
                                        <div class="card-body">
                                            <h3 class="p-price" id="item-price">{{ $item->final_price }} {{ $currency->currency_ar }}</h3>
                                            <h6>{{ $item->category->title_ar }}</h6>
                                            <h2 class="p-title">{{ $item->title }}</h2>
                                            @if ($item->offer == 1)
                                            <h3 class="Discount">
                                                <font>{{ $item->price_before_offer }} {{ $currency->currency_ar }}</font> <span class="DiscountBox">{{ $item->offer_percentage }}%</span>
                                            </h3>
                                            @endif
                                            
                                                <div class="quantity d-flex justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <p>Quantity</p>
                                                        <form action="{{ route('front.cart.update.count') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" value="put" name="_method" />
                                                            <input type="hidden" name="id" value="{{ $item->id }}" />
                                                            <div data-id="{{ $item->id }}" class="pro-qty"><span class="dec qtybtn">-</span><input type="text" name="count" value="{{ $item->count }}"><span class="inc qtybtn">+</span></div>
                                                        </form>
                                                    </div>
                                                    <form action="{{ route('front.delete.cart') }}" method="post">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input type="hidden" name="id" value="{{ $item->id }}"   />
                                                        <a href="#" type="submit" class="trash-btn delete-item"><i class="bi bi-trash"></i>  حذف</a>
                                                    </form>
                                                </div>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-12">
                            {{-- <div class="card DetailsPriceBox">
    
                                <div class="card-body m-3 p-0">
                                    <div class=" d-flex justify-content-between">
                                        <p class="card-text">Delivery</p>
                                        <p class="card-text">Free </p>
                                    </div>
                                    <div class=" d-flex justify-content-between">
                                        <h4 class="card-text">Total</h4>
                                        <h4 class="card-text">{{ $totalAdded }} {{ $currency->currency_ar }}</h4>
                                    </div>
    
                                </div>
                                <button type="button" class="PaymentMethod m-3 popUpBtn" data-bs-toggle="modal" data-bs-target="#Payment">
                                    Payment
                                  </button>
    
                            </div> --}}
                            <div class="card DetailsPriceBox">
    
                                <div class="card-body m-3 p-0">
                                    {{-- <div class=" d-flex justify-content-between">
                                        <p class="card-text">Delivery</p>
                                        <p class="card-text">Free </p>
                                    </div> --}}
                                    <div class=" d-flex justify-content-between">
                                        <h4 class="card-text">الإجمالى</h4>
                                        <h4 class="card-text">{{ $totalAdded }} {{ $currency->currency_ar }}</h4>
                                    </div>
    
                                </div>
    
                                {{-- <div class="PaymentMethod m-3">
    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault"><i class="bi bi-credit-card-2-front"></i>Visa or MasterCard</label>
                                    </div>
    
                                </div> --}}
                                <a href="#" class="PaymentMethod m-3 submit-payment">ادفع الآن</a>
    
    
                                <form action="{{ route('front.cart.payment.request') }}" method="post" role="form" class="email-form">
                                    @csrf
                                    <h6>أضف بريدك الإلكترونى لإستقبال الكود</h6>
                                    <input name="email" value="{{ auth()->guard('user')->user()->email }}" type="email" class="form-control form-control-lg form-control-a order-email" placeholder="البريد الإلكترونى" required="">
                                </form>
    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <!-- ======= cart Section ======= -->


</main>
<!-- End #main -->
@endsection

@push('scripts')
<script>
$(".inc, .dec").on("click", function() {
    $(this).parent('div').parent('form').submit()
})

$(".submit-payment").on("click", function(e) {
    var orderEmail = $(".order-email").val()

    e.preventDefault()

    if (orderEmail.length > 0) {
        $(".email-form").submit()
    }else {
        if ($(".email-required").length) {

        }else {
            $(".order-email").after(`
            <div style="margin-top:20px" class="alert alert-outline-danger mb-4 email-required" role="alert"> البريد الإلكترونى حقل مطلوب</div>
            `)
        }
    }
})
</script>
@endpush