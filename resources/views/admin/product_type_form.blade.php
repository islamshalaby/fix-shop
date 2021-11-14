@extends('admin.app')

@section('title' , __('messages.add_new_product_type'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_product_type') }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
                       
            <div class="form-group mb-4">
                <label for="type_en">{{ __('messages.type_en') }}</label>
                <input required type="text" name="type_en" class="form-control" id="type_en" placeholder="{{ __('messages.type_en') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="type_ar">{{ __('messages.type_ar') }}</label>
                <input required type="text" name="type_ar" class="form-control" id="type_ar" placeholder="{{ __('messages.type_ar') }}" value="" >
            </div>

            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection