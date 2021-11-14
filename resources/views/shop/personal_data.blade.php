@extends('shop.app')

@section('title' , __('messages.personal_data'))

@section('content')
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.personal_data') }}</h4>
             </div>
    </div>
    
    @if (session('status'))
        <div class="alert alert-danger mb-4" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
            <strong>Error!</strong> {{ session('status') }} </button>
        </div> 
    @endif

    <form method="post" action="" enctype="multipart/form-data" >
     @csrf
     <div class="form-group mb-4">
        <label for="">{{ __('messages.front_image') }}</label><br>
        <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['seller']['front_image'] }}"  />
    </div>
    <div class="custom-file-container" data-upload-id="myFirstImage">
        <label>{{ __('messages.upload') }} ({{ __('messages.front_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
        <label class="custom-file-container__custom-file" >
            <input type="file" name="front_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <span class="custom-file-container__custom-file__custom-file-control"></span>
        </label>
        <div class="custom-file-container__image-preview"></div>
    </div> 

    <div class="form-group mb-4">
        <label for="">{{ __('messages.back_image') }}</label><br>
        <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['seller']['back_image'] }}"  />
    </div>

    <div class="custom-file-container" data-upload-id="mySecondImage">
        <label>{{ __('messages.upload') }} ({{ __('messages.back_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
        <label class="custom-file-container__custom-file" >
            <input type="file" name="back_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <span class="custom-file-container__custom-file__custom-file-control"></span>
        </label>
        <div class="custom-file-container__image-preview"></div>
    </div> 
    <div class="form-group mb-4">
        <label for="name">{{ __('messages.name') }}</label>
        <input required type="text" name="name" class="form-control" id="name" placeholder="{{ __('messages.name') }}" value="{{ $data['seller']['name'] }}" >
    </div>

    <div class="form-group mb-4">
        <label for="phone">{{ __('messages.phone') }}</label>
        <input required type="text" class="form-control" id="phone" name="phone" placeholder="{{ __('messages.phone') }}" value="{{ $data['seller']['phone'] }}" >
    </div>
    <div class="form-group mb-4">
        <label for="shop">{{ __('messages.shop_name') }}</label>
        <input  type="text" class="form-control" id="shop" name="shop" placeholder="{{ __('messages.shop_name') }}" value="{{ $data['seller']['shop'] }}" >
    </div>

    <div class="form-group mb-4">
        <label for="id_number">{{ __('messages.id_number') }}</label>
        <input required type="text" class="form-control" id="id_number" name="id_number" placeholder="{{ __('messages.id_number') }}" value="{{ $data['seller']['phone'] }}" >
    </div>
    <div class="form-group mb-4">
        <label for="instagram">{{ __('messages.instagram') }}</label>
        <input  type="text" class="form-control" id="instagram" name="instagram" placeholder="{{ __('messages.instagram') }}" value="{{ $data['seller']['instagram'] }}" >
    </div>

    <div class="form-group mb-4">
        <label for="account_number">{{ __('messages.account_number') }}</label>
        <input required type="text" class="form-control" id="account_number" name="account_number" placeholder="{{ __('messages.account_number') }}" value="{{ $data['seller']['account_number'] }}" >
    </div>
    <div class="form-group mb-4">
        <label for="details">{{ __('messages.additional_information') }}</label>
        <input  type="text" class="form-control" id="details" name="details" placeholder="{{ __('messages.additional_information') }}" value="{{ $data['seller']['details'] }}" >
    </div>
    <br>
    <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
</form>
</div>

@endsection