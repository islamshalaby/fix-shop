@extends('admin.app')

@section('title' , __('messages.add_new_discount'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_discount') }}</h4>
                 </div>
        </div>
        <form action="{{ route('discounts.store') }}" method="post" >
            @csrf          
            <div class="form-group mb-4">
                <label for="value">{{ __('messages.discount_percentage') }}</label>
                <input required type="number" name="value" class="form-control" step="any" min="0"  id="value" placeholder="{{ __('messages.discount_percentage') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="min_products_number">{{ __('messages.min_products_count') }}</label>
                <input required type="text" name="min_products_number" class="form-control" id="min_products_number" placeholder="{{ __('messages.min_products_count') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="max_products_number">{{ __('messages.max_products_count') }}</label>
                <input required type="text" name="max_products_number" class="form-control" id="max_products_number" placeholder="{{ __('messages.max_products_count') }}" value="" >
            </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection