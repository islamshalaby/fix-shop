@extends('admin.app')

@section('title' , __('messages.add_new_shop'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_shop') }}</h4>
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
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.upload') }} ({{ __('messages.logo') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" required name="logo" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
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
                <label for="name">{{ __('messages.shop_name') }}</label>
                <select id="seller_id" name="seller_id" class="form-control">
                    <option selected>{{ __('messages.select') }}</option>
                    @foreach ( $data['sellers'] as $seller )
                    <option {{ isset($data['seller']) && $data['seller'] == $seller->id ? 'selected' : '' }} value="{{ $seller->id }}">{{ $seller->shop }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-4">
                <label for="email">{{ __('messages.email') }}</label>
                <input required type="text" name="email" class="form-control" id="email" placeholder="{{ __('messages.email') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="min_order_cost">{{ __('messages.min_order_cost') }}</label>
                <input type="number" step="any" min="0" name="min_order_cost" class="form-control" id="min_order_cost" placeholder="{{ __('messages.min_order_cost') }}" >
            </div>
            <div class="form-group mb-4">
                <label for="password">{{ __('messages.password') }}</label>
                <input required type="password" class="form-control" id="password" name="password" placeholder="{{ __('messages.password') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                    <input  type="checkbox" name="show_home" class="new-control-input all-permisssions">
                    <span class="new-control-indicator"></span><span class="new-chk-content">{{ __('messages.show_home') }}</span>
                </label>
            </div>
            
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection