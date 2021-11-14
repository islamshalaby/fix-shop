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
                    <h2 class="p-title">{{ $item->title_ar }}</h2>
                    @if ($item->offer == 1)
                    <h3 class="Discount">
                        <font>{{ $item->price_before_offer }} {{ $currency->currency_ar }}</font> <span class="DiscountBox">{{ $item->offer_percentage }}%</span>
                    </h3>
                    @endif
                    
                    <div class="quantity d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <p>Quantity</p>
                            <div data-id="{{ $item->id }}" class="pro-qty"><span class="dec qtybtn">-</span><input type="text" value="{{ $item->count }}"><span class="inc qtybtn">+</span></div>
                        </div>
                        <a href="#" type="submit" class="trash-btn"><i class="bi bi-trash"></i>  Delete</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        @endif
    </div>
</div>
<div class="col-lg-4 col-md-5 col-12">
    <div class="card DetailsPriceBox">

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

        <a href="Payment-ar.html" class="PaymentMethod m-3">Payment </a>

    </div>
</div>