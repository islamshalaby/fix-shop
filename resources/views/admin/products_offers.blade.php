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
        td, th{
            text-align: center !important;
            vertical-align: middle !important;
        }
        
    </style>
@endpush
@push('scripts')
<script src="/admin/plugins/table/datatable/custom_miscellaneous.js"></script>

@endpush
@section('content')
<div id="badgeCustom" class="col-lg-12 mx-auto layout-spacing">
    <div class="statbox widget box box-shadow">
        
        
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
                    <h4>{{ __('messages.recent_offers') }}
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
                            <th>{{ __('messages.total_quatity') }}</th>
                            <th>{{ __('messages.remaining_quantity') }}</th>
                            <th>{{ __('messages.price_before_discount') }}</th>
                            <th>{{ __('messages.price_after_discount') }}</th>
                            <th class="hide_col">{{ __('messages.offers') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $product)
                            <tr>
                                <td><?=$i;?></td>
                                <td class="hide_col"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_50,q_50/v1623672910/{{ isset($product->mainImage->image) ? $product->mainImage->image : $product->images[0]->image }}"  /></td>
                                <td>{{ App::isLocale('en') ? $product->title_en : $product->title_ar }}</td>
                                <td>{{ $product->multi_options == 0 ? $product->total_quatity : $product->multiOptions->sum("total_quatity") }}</td>
                                <td>{{ $product->multi_options == 0 ? $product->remaining_quantity : $product->multiOptions->sum("remaining_quantity") }}</td>
                                <td>
                                    @if($product->multi_options == 0)
                                    {{ $product->offer == 1 ? $product->price_before_offer . " " . __('messages.dinar') : $product->final_price . " " . __('messages.dinar') }}
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
                                
                                <td class="hide_col">
                                    <a class="btn btn-{{ $product->recent_offers == 0 ? 'warning' : 'danger' }} mb-2 mr-2 btn-rounded" href="{{ $product->recent_offers == 0 ? route('products.addToOffers', [$product->id, 1]) : route('products.removedFromOffers', [$product->id, 1]) }}" onclick='return confirm("{{ __('messages.are_you_sure') }}");'>
                                        {{ __('messages.recent_offers') }} <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </a>
                                    
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