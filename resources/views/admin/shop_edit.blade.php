@extends('admin.app')

@php
 $page_title = __('messages.edit_store') . $data['store']['name'];
@endphp

@section('title' , $page_title)

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ $page_title }}</h4>
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
        <form action="" autocomplete="do-not-show-ac" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="">{{ __('messages.current_logo') }}</label><br>
                <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['store']['logo'] }}"  />
            </div>
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.upload') }} ({{ __('messages.logo') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" name="logo" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>    
            <div class="form-group mb-4">
                <label for="">{{ __('messages.current_cover') }}</label><br>
                <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['store']['cover'] }}"  />
            </div> 
            <div class="custom-file-container" data-upload-id="mySecondImage">
                <label>{{ __('messages.upload') }} ({{ __('messages.cover') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a> ( 349x133 )</label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" name="cover" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
            <div class="form-group mb-4">
                <label for="email">{{ __('messages.email') }}</label>
                <input required type="text" name="email" class="form-control" id="email" placeholder="{{ __('messages.email') }}" value="{{ $data['store']['email'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="min_order_cost">{{ __('messages.min_order_cost') }}</label>
                <input type="number" step="any" min="0" name="min_order_cost" class="form-control" id="min_order_cost" placeholder="{{ __('messages.min_order_cost') }}" value="{{ $data['store']['min_order_cost'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="password">{{ __('messages.password') }}</label>
                <input type="password" autocomplete="do-not-show-ac" class="form-control" id="password" name="password" placeholder="{{ __('messages.password') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                    <input  type="checkbox" name="show_home" {{ $data['store']['show_home'] == 1 ? 'checked' : '' }} class="new-control-input all-permisssions">
                    <span class="new-control-indicator"></span><span class="new-chk-content">{{ __('messages.show_home') }}</span>
                </label>
            </div>
            
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection