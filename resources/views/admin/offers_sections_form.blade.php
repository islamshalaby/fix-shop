@extends('admin.app')

@section('title' , __('messages.add_new_offers_section'))
@push('scripts')
    <script>
        var ss = $(".tags").select2({
            tags: true,
        }),
            slider = "{{ __('messages.slider') }}"
        $("#typeSelect").on('change', function() {
            if ($(this).val() == 1) {
                $("#productsList").parent('.form-group').hide()
                $("#productsList").prop("disabled", true)
                $("#ads_check").show()
                $.ajax({
                    url : "/admin-panel/home_sections/fetch/" + $(this).val(),
                    type : 'GET',
                    success : function (data) {
                        
                        $("#ads_check").append(`
                        <div class="col-12" >
                            <label> ${slider} </label>
                        </div>
                        `)
                        data.forEach(function(ad) {
                            $("#ads_check").append(`
                                <div class="col-md-3" >
                                    <div >
                                        <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                                            <input name="products[]" value="${ad.id}" type="checkbox" class="new-control-input">
                                            <span class="new-control-indicator"></span><span class="new-chk-content"><img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/${ad.image}" /></span>
                                        </label>
                                    </div>     
                                </div>
                            `)
                        })
                        
                    }
                })
            }else {
                $("#ads_check").hide()
                $("#ads_check").html("")
                $("#productsList").parent('.form-group').show()
                $("#productsList").prop("disabled", false)
            }
        })
    </script>
@endpush

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_offers_section') }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" required name="icon" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>            
            <div class="form-group mb-4">
                <label for="title_en">{{ __('messages.title_en') }}</label>
                <input required type="text" name="title_en" class="form-control" id="title_en" placeholder="{{ __('messages.title_en') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="title_ar">{{ __('messages.title_ar') }}</label>
                <input required type="text" name="title_ar" class="form-control" id="title_ar" placeholder="{{ __('messages.title_ar') }}" value="" >
            </div>
            <div class="form-group mb-4">
                <label for="sort">{{ __('messages.sort') }}</label>
                <input required type="number" name="sort" class="form-control" id="sort" placeholder="{{ __('messages.sort') }}" value="" >
            </div>
            <div class="form-group" >
                <div class="col-12" >
                    <label> {{ __('messages.offer_type') }} </label>
                </div>
                <select id="typeSelect" name="type" class="form-control">
                    <option selected disabled>{{ __('messages.select') }}</option>
                    <option value="1">{{ __('messages.slider') }}</option>
                    <option value="2">{{ __('messages.normal') }}</option>
                </select>
            </div>
            <div style="display: none" id="ads_check" class="row" >
            </div>
            <div style="display:none" class="form-group" >
                <div class="col-12" >
                    <label> {{ __('messages.products') }} </label>
                </div>
                <select id="productsList" name="products[]" class="form-control tags" multiple="multiple">
                    @foreach ($data['products'] as $product)
                    <option value="{{ $product->id }}">{{ App::isLocale('en') ? $product->title_en : $product->title_ar }}</option>
                    @endforeach
                </select>
            </div>
            
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection