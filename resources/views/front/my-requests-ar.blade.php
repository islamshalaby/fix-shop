@extends('front.app')
@section('title', $meta->orders_title_ar)
@section('description', $meta->orders_description_ar)
@section('content')
<main id="main">

    <!-- =======  My requests ======= -->
    <section class="myRequests section-t8 p-top">
        <div class="container">
            <div class="title-wrap d-flex justify-content-between">
                <div class="title-box">
                    <h2 class="title-a">طلباتى</h2>
                </div>
            </div>
            <div class="row">
                @if (count($data['orders']) > 0)
                @foreach ($data['orders'] as $item)
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class=" d-flex justify-content-between">
                                <h6 class="card-text">رقم الطلب</h6>
                                <h6 class="card-text">{{ $item->order_number }}</h6>
                            </div>
                            <div class=" d-flex justify-content-between">
                                <h6 class="card-text">تاريخ الطلب</p>
                                    <h6 class="card-text">{{ $item->date }}</h6>
                            </div>

                            <a href="{{ route('front.orders.details', $item->id) }}" class="btn">تفاصيل الطلب</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                لا يوجد طلبات
                @endif
            </div>
        </div>
    </section>

    <!-- ======= Latest products Section ======= -->


</main>
<!-- End #main -->
@endsection