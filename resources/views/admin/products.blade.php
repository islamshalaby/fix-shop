@extends('admin.app')

@section('title' , __('messages.show_products'))

@push('styles')
    <style>
        .table > tbody > tr > td,
        .table > thead > tr > th {
            font-size : 10px
        }
        .dropdown-menu {
            height: 100px;
            overflow: auto
        }
        input[type=date] {
            padding: 0 !important;
            text-align: right !important
        }
        td{
            text-align: center !important;
            vertical-align: middle !important
        }
        .wide {
            min-width : 130px !important
        }
    </style>
@endpush

@push('scripts')
<script src="/admin/plugins/table/datatable/custom_miscellaneous.js"></script>
    <script>
        var language = "{{ Config::get('app.locale') }}",
            select = "{{ __('messages.select') }}",
            details = "{{ __('messages.details') }}",
            edit = "{{ __('messages.edit') }}",
            delte = "{{ __('messages.delete') }}"
            $("#category, #product_type").on("change", function () {
                $("#catproducts").submit()
            })


        $(".total_quatity").on("keyup", function () {
            var inputVal = Number($(this).val())
            
            $(this).parent(".form-group").nextAll(".new-inputs").remove()
            for (var input = 0; input < inputVal; input ++) {
                var num = inputVal - input
                $(this).parent('.form-group').after(`
                <div class="card col-6 new-inputs component-card_1">
                    <div class="form-group mb-4">
                        <label for="serials${num}">{{ __('messages.serial_number') }} ${num}</label>
                        <input type="text" name="serials[]" class="form-control" id="serials${num}" placeholder="{{ __('messages.serial_number') }} ${num}" value="" > <br />
                        <label for="valid_to${num}">{{ __('messages.valid_to') }} ${num}</label>
                        <input type="date" name="valid_to[]" class="form-control" id="valid_to${num}" placeholder="{{ __('messages.valid_to') }} ${num}" value="" >
                    </div>
                </div>
                `)
            }
            
            
        })
        
    </script>
@endpush

@section('content')
<div id="badgeCustom" class="col-lg-12 mx-auto layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">
            <form id="catproducts" method="get" action="">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="category">{{ __('messages.category') }}</label>
                    <select required id="category" name="category" class="form-control">
                        <option disabled selected>{{ __('messages.select') }}</option>
                        @foreach ( $data['categories'] as $category )
                        <option {{ app('request')->input('category') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ App::isLocale('en') ? $category->title_en : $category->title_ar }}</option>
                        @endforeach 
                    </select>
                </div>
            </div>
            </form>
            @if($data['expire'] == 'no')
            <a class="btn btn-primary" href="/admin-panel/products/show?expire=soon">{{ __('messages.expired_soon') }}</a>
            @endif

            @if($data['expire'] == 'soon')
            <a class="btn btn-primary" href="/admin-panel/products/show">{{ __('messages.return_all_products') }}</a>
            @endif            
        </div>
        
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        @if(Session::has('success'))
            <div class="alert alert-icon-left alert-light-success mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12" y2="17"></line></svg>
                <strong>{{ Session('success') }}</strong>
            </div>
        @endif
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_products') }}
                        <button data-show="0" class="btn btn-primary show_actions">{{ __('messages.hide_actions') }}</button>
                    </h4>
                    
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="html5-extension" class="table table-hover non-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th class="hide_col">{{ __('messages.image') }}</th>
                            <th>{{ __('messages.product_title') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.total_quatity') }}</th>
                            <th>{{ __('messages.remaining_quantity') }}</th>
                            <th>{{ __('messages.sold_quantity') }}</th>
                            <th>{{ __('messages.price_before_discount') }}</th>
                            <th>{{ __('messages.price_after_discount') }}</th>
                            <th>
                                {{ __('messages.offers') }}
                            </th>
                            <th>{{ __('messages.last-update_date') }}</th>
                            <th class="text-center hide_col">{{ __('messages.actions') }}</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['products'] as $product)
                            <tr>
                                <td><?=$i;?></td>
                                <td class="hide_col"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_50,q_50/v1623672910/{{ isset($product->mainImage->image) ? $product->mainImage->image : '' }}"  /></td>
                                <td>{{ App::isLocale('en') ? $product->title_en : $product->title_ar }}</td>
                                <td>{{ App::isLocale('en') ?  $product->category->title_en : $product->category->title_ar }}</td>
                                <td>{{ $product->multi_options == 0 ? $product->total_quatity : $product->multiOptions->sum("total_quatity") }}</td>
                                <td>{{ $product->remaining_quantity }}</td>
                                <td>{{ $product->sold_count }}</td>
                                <td>
                                    @if($product->multi_options == 0)
                                    {{ $product->offer == 1 ? ((double)$product->price_before_offer + (double)$product->vat_value) . " " . __('messages.dinar') : $product->final_price . " " . __('messages.dinar') }}
                                    @else
                                    {{ $product->offer == 1 ? __('messages.start_from') . $product->multiOptions[0]->price_before_offer . " " . __('messages.dinar') : __('messages.start_from') . $product->multiOptions[0]->final_price . " " . __('messages.dinar') }}
                                    @endif
                                </td>
                                <td>
                                    @if($product->multi_options == 0)
                                    {{ $product->final_price . " " . __('messages.dinar') }}
                                    @else
                                    {{ __('messages.start_from') . $product->multiOptions[0]->final_price . " " . __('messages.dinar') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($product->recent_offers == 1)
                                    <a class="btn btn-danger  mb-2 mr-2 rounded-circle" href="{{ route('products.offer.update_action', [$product->id, 0]) }}">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </a>
                                    @else
                                    <a class="btn btn-primary  mb-2 mr-2 rounded-circle" href="{{ route('products.offer.update_action', [$product->id, 1]) }}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
                                    @endif
                                </td>
                                <td>{{ $product->updated_at->format("d-m-y") }}</td>
                                
                                <td class="hide_col">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-dark btn-sm">
                                            {{ $product->hidden == 0 ? __('messages.visible') : __('messages.hidden') }}
                                        </button>
                                        <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuReference5" style="will-change: transform;">
                                          <a class="dropdown-item" href="{{ route('products.details', $product->id) }}">{{ __('messages.details') }}</a>
                                            @if(Auth::user()->update_data) 
                                            <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">{{ __('messages.edit') }}</a>
                                            @endif
                                            @if(Auth::user()->delete_data) 
                                            <a class="dropdown-item"  onclick='return confirm("{{ __('messages.are_you_sure') }}");' href="{{ route('products.delete', $product->id) }}">{{ __('messages.delete') }}</a>
                                            @endif 
                                            
                                        </div>
                                      </div>
                                </td>
                                       
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <td></td>    
                    <tfoot>
                </table>
            </div>
        </div>
        {{-- <div class="paginating-container pagination-solid">
            <ul class="pagination">
                <li class="prev"><a href="{{$data['categories']->previousPageUrl()}}">Prev</a></li>
                @for($i = 1 ; $i <= $data['categories']->lastPage(); $i++ )
                    <li class="{{ $data['categories']->currentPage() == $i ? "active" : '' }}"><a href="/admin-panel/categories/show?page={{$i}}">{{$i}}</a></li>               
                @endfor
                <li class="next"><a href="{{$data['categories']->nextPageUrl()}}">Next</a></li>
            </ul>
        </div>   --}}
        
    </div>  

@endsection

@push('scripts')
<script>
    
</script>
    
@endpush