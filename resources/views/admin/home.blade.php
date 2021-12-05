@extends('admin.app')

@section('title' , 'Admin Panel Home')


@section('content')
{{-- <div class="row" >
    <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h5 class="">{{ __('messages.orders')}}</h5>
                <ul class="tabs tab-pills">
                    <li><a href="javascript:void(0);" id="tb_1" class="tabmenu">{{ __('messages.Monthly') }}</a></li>
                </ul>
            </div>

            <div class="widget-content">
                <div class="tabs tab-content">
                    <div id="content_1" class="tabcontent"> 
                        <div id="revenueMonthly"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-chart-two">
            <div class="widget-heading">
                <h5 class="">{{ __('messages.orders') }}</h5>
            </div>
            <div class="widget-content">
                <div id="chart-2" class=""></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-table-two">

            <div class="widget-heading">
                <h5 class="">{{ __('messages.recent_orders') }}</h5>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><div class="th-content">{{ __('messages.order_number') }}</div></th>
                                <th><div class="th-content">{{ __('messages.order_date') }}</div></th>
                                <th><div class="th-content">{{ __('messages.user') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('messages.total_with_delivery') }}</div></th>
                                <th><div class="th-content">{{ __('messages.status') }}</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['recent_orders'] as $recent_order)
                            
                            <tr>
                                <td><div class="td-content"><a href="{{ route('orders.invoice', $recent_order->id) }}" >{{ $recent_order->main_order_number }}</a></div></td>
                                <td><div class="td-content product-brand">{{ $recent_order->created_at->format("y-m-d") }}</div></td>
                                <td><div class="td-content customer-name">{{ $recent_order->user->name }}</div></td>
                                <td><div class="td-content pricing"><span class="">{{ $recent_order->total_price . " " . __('messages.dinar') }}</span></div></td>
                                <td>
                                    <div class="td-content">
                                    @if($recent_order->status == 1)
                                    <span class="badge outline-badge-primary">{{ __('messages.opened') }}</span>
                                    @else
                                    <span class="badge outline-badge-danger">{{ __('messages.closed') }}</span>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                            
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-table-three">

            <div class="widget-heading">
                <h5 class="">{{ __('messages.top_selling_products') }}</h5>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><div class="th-content">{{ __('messages.product_title') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('messages.price_before_discount') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('messages.price_after_discount') }}</div></th>
                                <th><div class="th-content">{{ __('messages.sold') }}</div></th>
                                <th><div class="th-content">{{ __('messages.remaining_quantity') }}</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['most_sold_products'] as $sold_product)
                            <tr>
                                <td><div class="td-content product-name"><a href="{{ route('products.details', $sold_product->id) }}" >{{ App::isLocale('en') ? $sold_product->title_en : $sold_product->title_ar }}</a></div></td>
                                <td><div class="td-content"><span class="pricing">{{ $sold_product->multi_options == 0 ? $sold_product->price_before_offer . " " . __('messages.dinar') : $sold_product->option_price_before_offer . " " . __('messages.dinar') }}</span></div></td>
                                <td><div class="td-content"><span class="discount-pricing">{{ $sold_product->multi_options == 0 ? $sold_product->final_price . " " . __('messages.dinar') : $sold_product->option_price . " " . __('messages.dinar') }}</span></div></td>
                                <td><div class="td-content">{{ $sold_product->cnt }}</div></td>
                                <td><div class="td-content"><a href="javascript:void(0);" class="">{{ $sold_product->remaining_quantity  }}</a></div></td>
                            </tr>
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-table-two">

            <div class="widget-heading">
                <h5 class="">{{ __('messages.recent_refund') }}</h5>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><div class="th-content">{{ __('messages.refund_number') }}</div></th>
                                <th><div class="th-content">{{ __('messages.product') }}</div></th> 
                                <th><div class="th-content">{{ __('messages.date') }}</div></th>
                                <th><div class="th-content">{{ __('messages.user') }}</div></th>
                                <th><div class="th-content">{{ __('messages.status') }}</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['recent_refund_requests'] as $recent_refund)
                            
                            <tr>
                                <td><div class="td-content"><a target="_blank" href="{{ route('refund.details', $recent_refund->id) }}" >{{ $recent_refund->refund_number }}</a></div></td>
                                <td><div class="td-content"><a target="_blank" href="{{ route('products.details', $recent_refund->item->product->id) }}" >{{ App::isLocale('en') ? $recent_refund->item->product->title_en : $recent_refund->item->product->title_ar }}</a></div></td>
                                <td><div class="td-content product-brand">{{ $recent_refund->created_at->format("y-m-d") }}</div></td>
                                <td><div class="td-content customer-name">{{ $recent_refund->user->name }}</div></td>
                                <td>
                                    <div class="td-content">
                                        @if($recent_refund->item->status == 5)
                                        <span class="badge outline-badge-primary">{{ __('messages.refund_request') }}</span>
                                        @elseif ($recent_refund->item->status == 6)
                                        <span class="badge outline-badge-success">{{ __('messages.refund_accepted') }}</span>
                                        @else
                                        <span class="badge outline-badge-danger">{{ __('messages.refund_rejected') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-table-three">

            <div class="widget-heading">
                <h5 class="">{{ __('messages.top_refund_products') }}</h5>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><div class="th-content">{{ __('messages.product_title') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('messages.price_before_discount') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('messages.price_after_discount') }}</div></th>
                                <th><div class="th-content">{{ __('messages.refund') }}</div></th>
                                <th><div class="th-content">{{ __('messages.remaining_quantity') }}</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['most_refund_products'] as $refund_product)
                            <tr>
                                <td><div class="td-content product-name"><a target="_blank" href="{{ route('products.details', $refund_product->id) }}" >{{ App::isLocale('en') ? $refund_product->title_en : $refund_product->title_ar }}</a></div></td>
                                <td><div class="td-content"><span class="pricing">{{ $refund_product->option_price_before_offer . " " . __('messages.dinar') }}</span></div></td>
                                <td><div class="td-content"><span class="discount-pricing">{{ $refund_product->final_price . " " . __('messages.dinar') }}</span></div></td>
                                <td><div class="td-content">{{ $refund_product->cnt }}</div></td>
                                <td><div class="td-content"><a href="javascript:void(0);" class="">{{ $refund_product->remaining_quantity  }}</a></div></td>
                            </tr>
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <a href="/admin-panel/products/show?expire=soon" >
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">{{ $data['products_less_than_ten'] }}</h6>
                            <p class=""> {{ __('messages.products_less_than_ten') }}</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <a href="/admin-panel/users/show" >
            <div class="widget widget-card-four">
            <div class="widget-content">
                <div class="w-content">
                    <div class="w-info">
                        <h6 class="value"> {{ $data['users'] }}</h6>
                        <p class="">{{ __('messages.users_count') }}</p>
                    </div>
                    <div class="">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <a href="{{ route('ads.index') }}" >
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value"> {{ $data['ads'] }}</h6>
                            <p class="">{{ __('messages.ads_count') }}</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <a href="/admin-panel/contact_us" >
            <div class="widget widget-card-four">
            <div class="widget-content">
                <div class="w-content">
                    <div class="w-info">
                        <h6 class="value">{{ $data['contact_us'] }}</h6>
                        <p class=""> {{ __('messages.contact_us_count') }}</p>
                    </div>
                    <div class="">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            </div>
        </a>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <a href="{{ route('categories.index') }}" >
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">{{ $data['categories'] }}</h6>
                            <p class=""> {{ __('messages.categories_count') }}</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
        <a href="{{ route('sellers.requests.show') }}" >
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">{{ $data['sellers'] }}</h6>
                            <p class=""> {{ __('messages.stores_orders') }}</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
     --}}

                       
@endsection

