@extends('shop.app')

@php
    $option = App::isLocale('en') ? $data['option']['title_en'] : $data['option']['title_ar'];
    $page_title = __('messages.add_values_to_multi') . " " . $option
@endphp

@section('title' , $page_title)
@push('styles')
    <style>
        .bootstrap-tagsinput .tag {
            color : #3b3f5c
        }
        .bootstrap-tagsinput,
        .bootstrap-tagsinput input {
            width: 100%
        }
        .bootstrap-tagsinput {
            min-height : 45px
        }
    </style>
@endpush
@push('scripts')
    <script>
        // initialize select multiple plugin
        var ss = $(".tags").select2({
            tags: true,
        });
    </script>
@endpush
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
                <label for="title_en">{{ __('messages.value_en') }}</label><br/>
                <input  type="text" name="property_values_en" class="form-control" data-role="tagsinput"></input>
            </div>
            <div class="form-group mb-4">
                <label for="value_ar">{{ __('messages.value_ar') }}</label><br/>
                <input type="text" name="property_values_ar" class="form-control" data-role="tagsinput"></input>
            </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection