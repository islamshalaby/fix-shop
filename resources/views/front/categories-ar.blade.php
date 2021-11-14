@extends('front.app')
@section('title', $meta->categories_title_ar)
@section('description', $meta->categories_description_ar)
@section('content')
<main id="main">

    <!-- =======  products-list ======= -->
    <section class="products-list section-t8 ">
        <div class="container p-0">
            @if(count($web_image) > 0)
                <div class="bannerTop">
                    <a target="_blank" href="{{ $web_image[0]->type == 2 ? $web_image[0]->content : route('front.product_details_ar', $web_image[0]->content) }}">
                        <img src="https://res.cloudinary.com/al-thuraya/image/upload/v1581928924/{{$web_image[0]->image}}" alt="">
                    </a>
                </div>
            @endif
            <div class="title-wrap d-flex justify-content-between">
                <div class="title-box">
                    <h2 class="title-a"> الفئـــات</h2>
                </div>
            </div>
            <div class="row">
                @if (count($data['sub_categories']) > 0)
                    @foreach ($data['sub_categories'] as $cat)
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
    <!-- End products Section -->


</main>
<!-- End #main -->
@endsection

@push('scripts')
<script>
$(".inc, .dec").on("click", function() {
    var cartId = $(this).parent('.pro-qty').data('id'),
        count = $(this).siblings('input').attr('value'),
        url = `{{ route('front.cart') }}/${cartId}/${count}`,
        url2 = "{{ route('front.cart') }}"
        console.log(url)
    $.ajax({
        url : url,
        type : 'GET',
        success : function (data) {
            location.reload()

        }
    })
})

</script>
@endpush