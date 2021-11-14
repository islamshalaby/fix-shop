@extends('front.app')
@section('title', $meta->about_title_ar)
@section('description', $meta->about_description_ar)

@section('content')

    <main id="main">

        <!-- =======  About ======= -->
        <section class="section-About section-t8">
            <div class="container p-4">
                <div class="row">
                    <div class="col-lg-5">
                        <img src="https://res.cloudinary.com/al-thuraya/image/upload/v1581928924/{{$data->about_image}}" alt="">
                    </div>
                    <div class="col-lg-7">
                        <h6>عن الشركه </h6>
                        <h3>{{$data->about_title}}</h3>
                        <p>{{$data->about_desc}} </p>
                    </div>
                    <div class="col-lg-12 title-spot">
                        <h3>{{$data->about_footer}}</h3>
                    </div>
                </div>

            </div>
        </section>
        <!-- End about Section -->


    </main>
    <!-- End #main -->
@endsection
