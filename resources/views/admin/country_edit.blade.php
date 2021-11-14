@extends('admin.app')

@section('title' , __('messages.edit_country'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.edit_country') }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            
            <div class="form-group mb-4">
                <label for="">{{ __('messages.current_image') }}</label><br>
                <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $data['country']['icon'] }}"  />
            </div>
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.change_image') }} ({{ __('messages.flag') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" name="icon" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
            <div class="form-group mb-4">
                <label for="country_name">{{ __('messages.country_name') }}</label>
                <input required type="text" name="country_name" class="form-control" id="country_name" placeholder="{{ __('messages.country_name') }}" value="{{ $data['country']['country_name'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="country_code">Iso Code</label>
                <input required type="text" name="country_code" class="form-control" id="country_code" placeholder="Iso Code" value="{{ $data['country']['country_code'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="currency_en">{{ __('messages.currency_en') }}</label>
                <input required type="text" name="currency_en" class="form-control" id="currency_en" placeholder="{{ __('messages.currency_en') }}" value="{{ $data['country']['currency_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="currency_ar">{{ __('messages.currency_ar') }}</label>
                <input required type="text" name="currency_ar" class="form-control" id="country_name" placeholder="{{ __('messages.currency_ar') }}" value="{{ $data['country']['currency_ar'] }}" >
            </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection