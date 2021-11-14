@extends('admin.app')

@section('title' , __('messages.add_new_ad'))

@push('scripts')
    <script>

        $("#ad_type").on("change", function() {
            if(this.value == 2) {
                $(".outside").show()
                $('.productsParent').hide()
                $(".storesParent").hide()
                $("#storeselect").prop("disabled", true)
                $('select#products').prop("disabled", true)
                $(".outside input").prop("disabled", false)
                $(".inside").hide()
                $("#link_type").parent('.form-group').hide()
                $("#link_type").prop('disabled', true)
            }else {
                $(".outside").hide()
                $(".outside input").prop("disabled", true)

                $.ajax({
                    url : "/admin-panel/ads/fetchproducts",
                    type : 'GET',
                    success : function (data) {
                        $('.productsParent').show()
                        $('select#products').prop("disabled", false)
                        $('select#products').siblings('label').text(productSelect)
                        $('.storesParent').hide()
                        $('select#storeselect').prop("disabled", true)
                        data.forEach(function (product) {
                            var productName = product.title_en
                            if (language == 'ar') {
                                productName = product.title_ar
                            }
                            $('select#products').append(
                                "<option value='" + product.id + "'>" + productName + "</option>"
                            )
                        })
                    }
                })
            }
        })

        var language = "{{ Config::get('app.locale') }}",
                productSelect = "{{ __('messages.product') }}",
                categorySelect = "{{ __('messages.category') }}",
                storeSelect = "{{ __('messages.store') }}"

        $("#link_type").on("change", function() {
            $(".inside").show()
            $('select#products').html("")

            if ($(this).val() == 1) {

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
                        <h4>{{ __('messages.add_new_ad') }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" required name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
            {{--  <div class="form-group">
                <label for="place">{{ __('messages.ad_type') }}</label>
                <select id="place" name="place" class="form-control">
                    <option selected>{{ __('messages.select') }}</option>
                    <option value="1">{{ __('messages.inside_slider') }}</option>
                    <option value="2">{{ __('messages.normal_ad') }}</option>
                    <option value="3">{{ __('messages.product_ad') }} (1260 * 152)</option>
                    <option value="4">{{ __('messages.home_ads') }}</option>
                    
                </select>
            </div>  --}}
            <div class="form-group">
                <label for="sel1">{{ __('messages.ad_place') }}</label>
                <select id="ad_type" name="type" class="form-control">
                    <option selected>{{ __('messages.select') }}</option>
                    <option value="2">{{ __('messages.outside_the_app') }}</option>
                    <option value="1">{{ __('messages.inside_the_app') }}</option>
                </select>
            </div>

            <div style="display: none" class="form-group mb-4 outside">
                <label for="link">{{ __('messages.link') }}</label>
                <input required type="text" name="content" class="form-control" id="link" placeholder="{{ __('messages.link') }}" value="" >
            </div>

            <div style="display: none" class="form-group productsParent">
                <label for="products"></label>
                <select id="products" class="form-control" name="content">
                </select>
            </div>

            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
