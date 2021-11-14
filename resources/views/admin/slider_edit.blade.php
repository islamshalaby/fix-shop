@extends('admin.app')

@php
if ($data['slider']['type'] == 1) {
    $page_title = __('messages.home_page_slider');
}else if($data['slider']['type'] == 2) {
    $page_title = __('messages.category_page_slider');
}else {
    $page_title = __('messages.offers_slider');
}
@endphp
@section('title' , __('messages.sliders'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.home_page_slider') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="statbox widget box box-shadow">
            <form action="{{ route('sliders.update', 3) }}" method="post" enctype="multipart/form-data" >
                @csrf          
                
                <div class="row" >
                    <div class="col-12" >
                        <label> {{ __('messages.ads') }} </label>
                    </div>
                    @if(count($data['ads']) > 0)
                    
                        @foreach ($data['ads'] as $ad)
                        <div class="col-md-3" >
                            <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                                <input name="ads[]" {{ in_array($ad->id, $data['slider_ads']) ? 'checked' : '' }} value="{{ $ad->id }}" type="checkbox" class="new-control-input all-permisssions">
                                <span class="new-control-indicator"></span><span class="new-chk-content"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $ad->image }}"   /></span>
                            </label>
                            </div>
                            
                        </div>
                         
                        
                        @endforeach
                    @endif
                </div>
                
                <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
            </form>
        </div>
    </div>
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.home_page_bottom_slider') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="statbox widget box box-shadow">
            <form action="{{ route('sliders.update', 5) }}" method="post" enctype="multipart/form-data" >
                @csrf          
                
                <div class="row" >
                    <div class="col-12" >
                        <label> {{ __('messages.ads') }} </label>
                    </div>
                    @if(count($data['ads']) > 0)
                        @foreach ($data['ads'] as $ad)
                        <div class="col-md-3" >
                            <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                                <input name="ads[]" {{ in_array($ad->id, $data['slider_bottom_ads']) ? 'checked' : '' }} value="{{ $ad->id }}" type="checkbox" class="new-control-input all-permisssions">
                                <span class="new-control-indicator"></span><span class="new-chk-content"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $ad->image }}"   /></span>
                            </label>
                        </div>     
                    </div>
                        @endforeach
                    @endif
                </div>
                
                <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
            </form>
        </div>

    </div>
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.category_page_slider') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="statbox widget box box-shadow">
            <form action="{{ route('sliders.update', 6) }}" method="post" enctype="multipart/form-data" >
                @csrf          
                
                <div class="row" >
                    <div class="col-12" >
                        <label> {{ __('messages.ads') }} </label>
                    </div>
                    @if(count($data['ads']) > 0)
                        @foreach ($data['ads'] as $ad)
                        <div class="col-md-3" >
                            <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                                <input name="ads[]" {{ in_array($ad->id, $data['slider_categories_ads']) ? 'checked' : '' }} value="{{ $ad->id }}" type="checkbox" class="new-control-input all-permisssions">
                                <span class="new-control-indicator"></span><span class="new-chk-content"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $ad->image }}"   /></span>
                            </label>
                        </div>     
                    </div>
                        @endforeach
                    @endif
                </div>
                
                <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection