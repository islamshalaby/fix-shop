@extends('admin.app')

@php
 $page_title = App::isLocale('en') ? __('messages.edit_delivery_cost') . $data['area']['title_en'] . " ( " . $data['cost']->store->name . " )" : __('messages.edit_delivery_cost') . $data['area']['title_ar'] . " ( " . $data['cost']->store->name . " )";
@endphp

@section('title' , $page_title )

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ $page_title }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="delivery_cost">{{ __('messages.delivery_cost') }}</label>
                <input required type="number" step="any" min="0" name="delivery_cost" class="form-control" id="delivery_cost" placeholder="{{ __('messages.delivery_cost') }}" value="{{ $data['cost']['delivery_cost'] }}" >
            </div>
            {{-- <div class="form-group mb-4">
                <label for="min_order_cost">{{ __('messages.min_order_cost') }}</label>
                <input required type="number" step="any" min="0" name="min_order_cost" class="form-control" id="min_order_cost" placeholder="{{ __('messages.min_order_cost') }}" value="{{ $data['cost']['min_order_cost'] }}" >
            </div> --}}
            <div class="form-group mb-4">
                <label for="estimated_arrival_time">{{ __('messages.estimated_arrival_time') }} ( {{ __('messages.minutes') }} )</label>
                <input required type="number" step="any" min="0" name="estimated_arrival_time" class="form-control" id="estimated_arrival_time" placeholder="{{ __('messages.estimated_arrival_time') }}" value="{{ $data['cost']['estimated_arrival_time'] }}" >
            </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection