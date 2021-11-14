@extends('admin.app')

@section('title' , __('messages.ad_edit'))

@push('scripts')
    <script>
        var language = "{{ Config::get('app.locale') }}",
            productSelect = "{{ __('messages.product') }}",
            categorySelect = "{{ __('messages.category') }}",
            storeSelect = "{{ __('messages.store') }}"
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

        $("#link_type").on("change", function() {
            $(".inside").show()
            $('select#products').html("")

            if ($(this).val() == 1) {
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
            }else if($(this).val() == 3 || $(this).val() == 2) {
                var itemSelect = $(this)
                $.ajax({
                    url : "/admin-panel/ads/fetchstores",
                    type : 'GET',
                    success : function (data) {
                        if (itemSelect.val() == 3) {
                            $('.productsParent').show()
                            $('select#products').prop("disabled", false)
                            $('.storesParent').hide()
                            $('select#storeselect').prop("disabled", true)
                            $('select#products').siblings('label').text(storeSelect)
                            data.forEach(function (store) {
                                $('select#products').append(
                                    "<option value='" + store.id + "'>" + store.name + "</option>"
                                )
                            })
                        }else {
                            $('.storesParent').show()
                            $('select#storeselect').html('')
                            $('select#storeselect').prop("disabled", false)
                            $('select#storeselect').siblings('label').text(storeSelect)
                            $('.productsParent').hide()
                            $('select#products').prop("disabled", true)
                            data.forEach(function (store) {
                                $('select#storeselect').append(
                                    "<option value='" + store.id + "'>" + store.name + "</option>"
                                )
                            })
                        }

                    }
                })
            }
        })

        $(".storesParent #storeselect").on("change", function () {
            var store = $(this).val()
            $('select#products').html('')
            $.ajax({
                url : "/admin-panel/ads/fetchstorecategories/" + store,
                type : 'GET',
                success : function (data) {
                    console.log(data)
                    $('.productsParent').show()
                    $('select#products').prop("disabled", false)
                    $('select#products').siblings('label').text(categorySelect)
                    data.forEach(function (category) {
                        var categoryName = category.title_en
                        if (language == 'ar') {
                            categoryName = category.title_ar
                        }
                        $('select#products').append(
                            "<option value='" + category.id + "'>" + categoryName + "</option>"
                        )
                    })
                }
            })
        })

        @if ($data['ad']['type'] == 1)
        var contentId = {{ $data['ad']['content'] }}
            @if($data['ad']['content_type'] == 1)
                $.ajax({
                    url : "/admin-panel/ads/fetchproducts",
                    type : 'GET',
                    success : function (data) {
                        $('.productsParent').show()
                        $('select#products').prop("disabled", false)
                        $('select#products').siblings('label').text(productSelect)
                        data.forEach(function (product) {
                            var productName = product.title_en,
                                selected = ""

                            if (contentId == product.id) {
                                selected = 'selected'
                            }
                            if (language == 'ar') {
                                productName = product.title_ar
                            }
                            $('select#products').append(
                                "<option " + selected + " value='" + product.id + "'>" + productName + "</option>"
                            )
                        })
                    }
                })
            @endif
            var itemSelect = {{ $data['ad']['content_type'] }}
            @if($data['ad']['content_type'] == 2 || $data['ad']['content_type'] == 3)
            $.ajax({
                url : "/admin-panel/ads/fetchstores",
                type : 'GET',
                success : function (data) {
                    if (itemSelect == 3) {
                        $('.productsParent').show()
                        $('select#products').prop("disabled", false)
                        $('.storesParent').hide()
                        $('select#storeselect').prop("disabled", true)
                        $('select#products').siblings('label').text(storeSelect)
                        data.forEach(function (store) {
                            $('select#products').append(
                                "<option value='" + store.id + "'>" + store.name + "</option>"
                            )
                        })
                    }else {
                        var storeId = {{ $data['ad']['store_id'] }}

                        $('.storesParent').show()
                        $('select#storeselect').html('')
                        $('select#storeselect').prop("disabled", false)
                        $('select#storeselect').siblings('label').text(storeSelect)
                        $('.productsParent').hide()
                        $('select#products').prop("disabled", true)
                        data.forEach(function (store) {
                            var selected = ""
                            if (storeId == store.id) {
                                selected = "selected"
                                console.log(selected)
                            }
                            $('select#storeselect').append(
                                "<option " + selected + " value='" + store.id + "'>" + store.name + "</option>"
                            )
                        })
                    }

                }
            })

                @if($data['ad']['content_type'] == 2)
                var store = {{ $data['ad']['store_id'] }}
                $.ajax({
                    url : "/admin-panel/ads/fetchstorecategories/" + store,
                    type : 'GET',
                    success : function (data) {
                        var content = {{ $data['ad']['content'] }}
                        $('.productsParent').show()
                        $('select#products').prop("disabled", false)
                        $('select#products').siblings('label').text(categorySelect)
                        data.forEach(function (category) {
                            var categoryName = category.title_en,
                                selected = ""
                            if (language == 'ar') {
                                categoryName = category.title_ar
                            }
                            if (content == category.id) {
                                selected = "selected"
                            }
                            $('select#products').append(
                                "<option " + selected + " value='" + category.id + "'>" + categoryName + "</option>"
                            )
                        })
                    }
                })
                @endif
            @endif
        @endif


    </script>
@endpush

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.ad_edit') }}</h4>
                 </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="form-group mb-4">
                <label for="">{{ __('messages.current_image') }}</label><br>
                <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $data['ad']['image'] }}"  />
            </div>
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.change_image') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>

            <div class="form-group">
                <label for="place">{{ __('messages.ad_type') }}</label>
                <select id="place" required name="place" class="form-control">
                    <option >{{ __('messages.select') }}</option>
                    <option {{ $data['ad']['place'] == 1 ? 'selected' : '' }} value="1">{{ __('messages.inside_slider') }}</option>
                    <option {{ $data['ad']['place'] == 2 ? 'selected' : '' }} value="2">{{ __('messages.normal_ad') }}</option>
                    <option {{ $data['ad']['place'] == 3 ? 'selected' : '' }} value="3">{{ __('messages.product_ad') }} (1260 * 152)</option>
                    <option {{ $data['ad']['place'] == 4 ? 'selected' : '' }} value="4">{{ __('messages.home_ads') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sel1">{{ __('messages.ad_place') }}</label>
                <select id="ad_type" name="type" class="form-control">
                    <option selected>{{ __('messages.select') }}</option>
                    <option {{ $data['ad']['type'] == 2 ? 'selected' : '' }} value="2">{{ __('messages.outside_the_app') }}</option>
                    <option {{ $data['ad']['type'] == 1 ? 'selected' : '' }} value="1">{{ __('messages.inside_the_app') }}</option>
                </select>
            </div>
            
            <div style="display: none" class="form-group storesParent">
                <label for="storeselect"></label>
                <select id="storeselect" class="form-control" name="store_id">
                </select>
            </div>
            <div style="display: {{ $data['ad']['type'] == 1 ? 'none' : '' }}" class="form-group mb-4 outside">
                <label for="link">{{ __('messages.link') }}</label>
                <input required type="text" name="content" class="form-control" id="link" placeholder="{{ __('messages.link') }}" value="{{ $data['ad']['content'] }}" >
            </div>


            <div style="{{ $data['ad']['type'] == 1 ? '' : 'display: none' }}" class="form-group productsParent">
                <label for="products">{{ __('messages.product') }}</label>
                <select id="products" class="form-control" name="content">
                    @if (count($data['products']) > 0)
                    @foreach ($data['products'] as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $data['ad']['content'] ? 'selected' : '' }} >{{ App::isLocale('en') ? $item->title_en : $item->title_ar }}</option>
                    @endforeach
                    @endif
                </select>
            </div>

            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection
