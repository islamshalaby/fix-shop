@extends('admin.app')


@section('title' , __('messages.edit_slider'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.edit_slider') }}</h4>
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
            <form action="" method="post" enctype="multipart/form-data" >
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
                                    <input {{ $data['slider']->ad_id == $ad->id ? 'checked' : '' }} name="ad_id" value="{{ $ad->id }}" type="radio" class="new-control-input all-permisssions">
                                    <span class="new-control-indicator"></span><span class="new-chk-content"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $ad->image }}"   /></span>
                                </label>
                            </div>
                        </div>
                        
                        @endforeach
                    @endif
                    
                </div>
                <div class="form-group mb-4">
                    <label for="text1_en">{{ __('messages.text1_en') }}</label>
                    <input required type="text" name="text1_en" class="form-control" id="text1_en" placeholder="{{ __('messages.text1_en') }}" value="{{ $data['slider']->text1_en }}" >
                </div>
                <div class="form-group mb-4">
                    <label for="text1_ar">{{ __('messages.text1_ar') }}</label>
                    <input required type="text" name="text1_ar" class="form-control" id="text1_ar" placeholder="{{ __('messages.text1_ar') }}" value="{{ $data['slider']->text1_ar }}" >
                </div>
                <div class="form-group mb-4">
                    <label for="text2_en">{{ __('messages.text2_en') }}</label>
                    <input required type="text" name="text2_en" class="form-control" id="text2_en" placeholder="{{ __('messages.text2_en') }}" value="{{ $data['slider']->text2_en }}" >
                </div>
                <div class="form-group mb-4">
                    <label for="text2_ar">{{ __('messages.text2_ar') }}</label>
                    <input required type="text" name="text2_ar" class="form-control" id="text2_ar" placeholder="{{ __('messages.text2_ar') }}" value="{{ $data['slider']->text2_ar }}" >
                </div>
                <div class="form-group mb-4">
                    <label for="text3_en">{{ __('messages.text3_en') }}</label>
                    <input required type="text" name="text3_en" class="form-control" id="text1_en" placeholder="{{ __('messages.text3_en') }}" value="{{ $data['slider']->text3_en }}" >
                </div>
                <div class="form-group mb-4">
                    <label for="text3_ar">{{ __('messages.text3_ar') }}</label>
                    <input required type="text" name="text3_ar" class="form-control" id="text3_ar" placeholder="{{ __('messages.text3_ar') }}" value="{{ $data['slider']->text3_ar }}" >
                </div>
                <div class="form-group mb-4">
                    <label for="highlighted">{{ __('messages.highlighted_text') }}</label>
                    <input required type="text" name="highlighted" class="form-control" id="highlighted" placeholder="{{ __('messages.highlighted_text') }}" value="{{ $data['slider']->highlighted }}" >
                </div>

                <div class="form-group mb-4">
                    <label for="price">{{ __('messages.price') }}</label>
                    <input required type="text" name="price" class="form-control" id="price" placeholder="{{ __('messages.price') }}" value="{{ $data['slider']->price }}" >
                </div>
                
                <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
            </form>
        </div>
    </div>
    
    
@endsection