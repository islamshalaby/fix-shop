@extends('admin.app')

@section('title' , 'Admin Panel Edit Ad')

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.meta_tags') }}</h4>
                 </div>
        </div>
        <form action="" method="post" >
            @csrf
            <div class="form-group mb-4">
                <label for="home_meta_en">{{ __('messages.meta_key_words_en') }}</label>
                <textarea name="home_meta_en" class="form-control" id="home_meta_en" rows="5">{{ $data['meta']['home_meta_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="home_meta_ar">{{ __('messages.meta_key_words_ar') }}</label>
                <textarea name="home_meta_ar" class="form-control" id="home_meta_ar" rows="5">{{ $data['meta']['home_meta_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="home_title_en">{{ __('messages.home_title_en') }}</label>
                <input type="text" name="home_title_en" class="form-control" id="home_title_en" placeholder="{{ __('messages.home_title_en') }}" value="{{ $data['meta']['home_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="home_title_ar">{{ __('messages.home_title_ar') }}</label>
                <input type="text" name="home_title_ar" class="form-control" id="home_title_ar" placeholder="{{ __('messages.home_title_ar') }}" value="{{ $data['meta']['home_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="home_description_en">{{ __('messages.home_description_en') }}</label>
                <textarea name="home_description_en" class="form-control" id="home_description_en" rows="5">{{ $data['meta']['home_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="home_description_ar">{{ __('messages.home_description_ar') }}</label>
                <textarea name="home_description_ar" class="form-control" id="home_description_ar" rows="5">{{ $data['meta']['home_description_ar'] }}</textarea>
            </div>     
            <div class="form-group mb-4">
                <label for="contact_title_en">{{ __('messages.contact_title_en') }}</label>
                <input type="text" name="contact_title_en" class="form-control" id="contact_title_en" placeholder="{{ __('messages.contact_title_en') }}" value="{{ $data['meta']['contact_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="contact_title_ar">{{ __('messages.contact_title_ar') }}</label>
                <input type="text" name="contact_title_ar" class="form-control" id="contact_title_ar" placeholder="{{ __('messages.contact_title_ar') }}" value="{{ $data['meta']['contact_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="contact_description_en">{{ __('messages.contact_description_en') }}</label>
                <textarea name="contact_description_en" class="form-control" id="contact_description_en" rows="5">{{ $data['meta']['contact_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="contact_description_ar">{{ __('messages.contact_description_ar') }}</label>
                <textarea name="contact_description_ar" class="form-control" id="contact_description_ar" rows="5">{{ $data['meta']['contact_description_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="about_title_en">{{ __('messages.about_title_en') }}</label>
                <input type="text" name="about_title_en" class="form-control" id="about_title_en" placeholder="{{ __('messages.about_title_en') }}" value="{{ $data['meta']['about_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="about_title_ar">{{ __('messages.about_title_ar') }}</label>
                <input type="text" name="about_title_ar" class="form-control" id="about_title_ar" placeholder="{{ __('messages.about_title_ar') }}" value="{{ $data['meta']['about_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="about_description_en">{{ __('messages.about_description_en') }}</label>
                <textarea name="about_description_en" class="form-control" id="about_description_en" rows="5">{{ $data['meta']['about_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="about_description_ar">{{ __('messages.about_description_ar') }}</label>
                <textarea name="about_description_ar" class="form-control" id="about_description_ar" rows="5">{{ $data['meta']['about_description_ar'] }}</textarea>
            </div> 
            <div class="form-group mb-4">
                <label for="categories_title_en">{{ __('messages.cats_page_title_en') }}</label>
                <input type="text" name="categories_title_en" class="form-control" id="categories_title_en" placeholder="{{ __('messages.cats_page_title_en') }}" value="{{ $data['meta']['categories_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="categories_title_ar">{{ __('messages.cats_page_title_ar') }}</label>
                <input type="text" name="categories_title_ar" class="form-control" id="categories_title_ar" placeholder="{{ __('messages.cats_page_title_ar') }}" value="{{ $data['meta']['categories_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="categories_description_en">{{ __('messages.cats_page_description_en') }}</label>
                <textarea name="categories_description_en" class="form-control" id="categories_description_en" rows="5">{{ $data['meta']['categories_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="categories_description_ar">{{ __('messages.cats_page_description_ar') }}</label>
                <textarea name="categories_description_ar" class="form-control" id="categories_description_ar" rows="5">{{ $data['meta']['categories_description_ar'] }}</textarea>
            </div> 
            <div class="form-group mb-4">
                <label for="products_title_en">{{ __('messages.prods_page_title_en') }}</label>
                <input type="text" name="products_title_en" class="form-control" id="products_title_en" placeholder="{{ __('messages.prods_page_title_en') }}" value="{{ $data['meta']['products_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="products_title_ar">{{ __('messages.prods_page_title_ar') }}</label>
                <input type="text" name="products_title_ar" class="form-control" id="products_title_ar" placeholder="{{ __('messages.prods_page_title_ar') }}" value="{{ $data['meta']['products_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="products_description_en">{{ __('messages.prods_page_description_en') }}</label>
                <textarea name="products_description_en" class="form-control" id="products_description_en" rows="5">{{ $data['meta']['products_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="products_description_ar">{{ __('messages.prods_page_description_ar') }}</label>
                <textarea name="products_description_ar" class="form-control" id="products_description_ar" rows="5">{{ $data['meta']['products_description_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="offers_title_en">{{ __('messages.offers_page_title_en') }}</label>
                <input type="text" name="offers_title_en" class="form-control" id="offers_title_en" placeholder="{{ __('messages.offers_page_title_en') }}" value="{{ $data['meta']['offers_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="offers_title_ar">{{ __('messages.offers_page_title_ar') }}</label>
                <input type="text" name="offers_title_ar" class="form-control" id="offers_title_ar" placeholder="{{ __('messages.offers_page_title_ar') }}" value="{{ $data['meta']['offers_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="offers_description_en">{{ __('messages.offers_page_description_en') }}</label>
                <textarea name="offers_description_en" class="form-control" id="offers_description_en" rows="5">{{ $data['meta']['offers_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="offers_description_ar">{{ __('messages.offers_page_description_ar') }}</label>
                <textarea name="offers_description_ar" class="form-control" id="offers_description_ar" rows="5">{{ $data['meta']['offers_description_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="orders_title_en">{{ __('messages.orders_page_title_en') }}</label>
                <input type="text" name="orders_title_en" class="form-control" id="orders_title_en" placeholder="{{ __('messages.orders_page_title_en') }}" value="{{ $data['meta']['orders_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="orders_title_ar">{{ __('messages.orders_page_title_ar') }}</label>
                <input type="text" name="orders_title_ar" class="form-control" id="orders_title_ar" placeholder="{{ __('messages.orders_page_title_ar') }}" value="{{ $data['meta']['orders_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="orders_description_en">{{ __('messages.orders_page_description_en') }}</label>
                <textarea name="orders_description_en" class="form-control" id="orders_description_en" rows="5">{{ $data['meta']['orders_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="orders_description_ar">{{ __('messages.orders_page_description_ar') }}</label>
                <textarea name="orders_description_ar" class="form-control" id="orders_description_ar" rows="5">{{ $data['meta']['orders_description_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="cart_title_en">{{ __('messages.cart_page_title_en') }}</label>
                <input type="text" name="cart_title_en" class="form-control" id="cart_title_en" placeholder="{{ __('messages.cart_page_title_en') }}" value="{{ $data['meta']['cart_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="cart_title_ar">{{ __('messages.cart_page_title_ar') }}</label>
                <input type="text" name="cart_title_ar" class="form-control" id="cart_title_ar" placeholder="{{ __('messages.cart_page_title_ar') }}" value="{{ $data['meta']['cart_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="cart_description_en">{{ __('messages.cart_page_description_en') }}</label>
                <textarea name="cart_description_en" class="form-control" id="cart_description_en" rows="5">{{ $data['meta']['cart_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="cart_description_ar">{{ __('messages.cart_page_description_ar') }}</label>
                <textarea name="cart_description_ar" class="form-control" id="cart_description_ar" rows="5">{{ $data['meta']['cart_description_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="conditions_title_en">{{ __('messages.condition_page_title_en') }}</label>
                <input type="text" name="conditions_title_en" class="form-control" id="conditions_title_en" placeholder="{{ __('messages.condition_page_title_en') }}" value="{{ $data['meta']['conditions_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="conditions_title_ar">{{ __('messages.condition_page_title_ar') }}</label>
                <input type="text" name="conditions_title_ar" class="form-control" id="conditions_title_ar" placeholder="{{ __('messages.condition_page_title_ar') }}" value="{{ $data['meta']['conditions_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="conditions_description_en">{{ __('messages.condition_page_description_en') }}</label>
                <textarea name="conditions_description_en" class="form-control" id="conditions_description_en" rows="5">{{ $data['meta']['conditions_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="conditions_description_ar">{{ __('messages.condition_page_description_ar') }}</label>
                <textarea name="conditions_description_ar" class="form-control" id="conditions_description_ar" rows="5">{{ $data['meta']['conditions_description_ar'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="privacy_title_en">{{ __('messages.privacy_page_title_en') }}</label>
                <input type="text" name="privacy_title_en" class="form-control" id="privacy_title_en" placeholder="{{ __('messages.privacy_page_title_en') }}" value="{{ $data['meta']['privacy_title_en'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="privacy_title_ar">{{ __('messages.privacy_page_title_ar') }}</label>
                <input type="text" name="privacy_title_ar" class="form-control" id="privacy_title_ar" placeholder="{{ __('messages.privacy_page_title_ar') }}" value="{{ $data['meta']['privacy_title_ar'] }}" >
            </div>
            <div class="form-group mb-4">
                <label for="privacy_description_en">{{ __('messages.privacy_page_description_en') }}</label>
                <textarea name="privacy_description_en" class="form-control" id="privacy_description_en" rows="5">{{ $data['meta']['privacy_description_en'] }}</textarea>
            </div>
            <div class="form-group mb-4">
                <label for="privacy_description_ar">{{ __('messages.privacy_page_description_ar') }}</label>
                <textarea name="privacy_description_ar" class="form-control" id="privacy_description_ar" rows="5">{{ $data['meta']['privacy_description_ar'] }}</textarea>
            </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection