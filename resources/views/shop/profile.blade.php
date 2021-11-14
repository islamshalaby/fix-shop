@extends('shop.app')

@section('title' , 'Shop profile')

@section('content')
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.update_profile') }}</h4>
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
        <label for="">{{ __('messages.current_logo') }}</label><br>
        <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['logo'] }}"  />
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
    @if(!empty($data['cover']))
    <div class="form-group mb-4">
        <label for="">{{ __('messages.current_cover') }}</label><br>
        <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['cover'] }}"  />
    </div>
    @endif
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
        <label for="name">{{ __('messages.store_name') }}</label>
        <input required type="text" name="name" class="form-control" id="name" placeholder="{{ __('messages.manager_name') }}" value="{{ $data['name'] }}" >
    </div>

    <div class="form-group mb-4">
        <label for="email">{{ __('messages.manager_email') }}</label>
        <input required type="Email" class="form-control" id="email" name="email" placeholder="{{ __('messages.manager_email') }}" value="{{ $data['email'] }}" >
    </div>
    <div class="form-group mb-4">
        <label for="password">{{ __('messages.password') }}</label>
        <input  type="password" class="form-control" id="password" name="password" placeholder="{{ __('messages.password') }}" value="" >
    </div>
    <br>
    <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
</form>
</div>

@endsection