@extends('admin.app')

@section('title' , __('messages.add_property'))

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
                        <h4>{{ __('messages.add_property') }}</h4>
                 </div>
        </div>
        @if(Session::has('fail'))
            <div class="alert alert-arrow-right alert-icon-right alert-light-danger mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                    <strong>{{ Session('fail') }}</strong>
            </div>
        @endif
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
                      
            <div class="form-group mb-4">
                <label for="title_en">{{ __('messages.title_en') }}</label>
                <input required type="text" name="title_en" class="form-control" id="title_en" placeholder="{{ __('messages.title_en') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                <input required type="text" name="title_ar" class="form-control" id="title_ar" placeholder="{{ __('messages.title_ar') }}" value="" >
            </div>
            <div class="form-group">
                <label for="category_select">{{ __('messages.category') }}</label>
                <select id="category_select" name="category_ids[]" class="form-control tags" multiple="multiple">
                    @foreach ( $data['categories'] as $category )
                    <option value="{{ $category->id }}">{{ App::isLocale('en') ? $category->title_en : $category->title_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-4">
                <label for="title_en">{{ __('messages.value_en') }} ( {{ __('messages.values_number_should') }} )</label><br/>
                <input type="text" name="property_values_en" class="form-control" data-role="tagsinput"></input>
            </div>
            <div class="form-group mb-4">
                <label for="title_en">{{ __('messages.value_ar') }} ( {{ __('messages.values_number_should') }} )</label><br/>
                <input type="text" name="property_values_ar" class="form-control" data-role="tagsinput"></input>
            </div>
            
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection