@extends('admin.app')

@section('title' , __('messages.add_new_slider'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_slider') }}</h4>
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
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf          
            <div class="form-group mb-4">
                <label for="type">{{ __('messages.slider_type') }}</label>
                <select id="type" name="type" class="form-control">
                    <option selected>{{ __('messages.select') }}</option>
                    <option value="1">{{ __('messages.home_page_slider') }}</option>
                    <option value="2">{{ __('messages.category_page_slider') }}</option>
                </select>
            </div>
            <div class="row" >
                <div class="col-12" >
                    <label> {{ __('messages.ads') }} </label>
                </div>
                @if(count($data['ads']) > 0)
                    @foreach ($data['ads'] as $ad)
                    <div class="col-md-3" >
                        <div class="n-chk">
                           <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                             <input name="ads[]" value="{{ $ad->id }}" type="checkbox" class="new-control-input all-permisssions">
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
@endsection