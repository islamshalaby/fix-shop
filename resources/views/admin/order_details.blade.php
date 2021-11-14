@extends('admin.app')

@section('title' , __('messages.order_details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.order_details') }} 
                        ( <a style="color: #1b55e2" target="_blank" href="{{ route('orders.invoice', $data['order']['id']) }}">
                            {{ __('messages.invoice') }}
                        </a> )
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.main_order_number') }}</td>
                            <td>
                                {{ $data['order']['order_number'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.invoice') }}</td>
                            <td>
                                <a target="_blank" href="{{ route('webview.invoice', $data['order']['id']) }}">
                                    {{ __('messages.invoice') }}
                                </a>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="label-table" > {{ __('messages.order_date') }}</td>
                            <td>
                                {{ $data['order']['created_at']->format("Y-m-d") }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.user') }} </td>
                            <td>
                                <a target="_blank" href="{{ route('users.details', $data['order']->user->id) }}">
                                    {{ $data['order']->user->name }}
                                </a>
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.payment_method') }} </td>
                            <td>
                                {{ __('messages.key_net') }}
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.price') }} </td>
                            <td>
                                {{ $data['order']['subtotal_price'] . " " . __('messages.usd') }}
                            </td>
                        </tr>  
                        
                        <tr>
                            <td class="label-table" > {{ __('messages.total') }} </td>
                            <td>
                                {{ $data['order']['total_price'] . " " . __('messages.usd') }}
                            </td>
                        </tr>
                       
                    </tbody>
                </table>
                
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ __('messages.product_price') }}</th>
                            <th>{{ __('messages.serial_number') }}</th>
                            <th>{{ __('messages.count') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['order']->oItems as $item)
                        <tr id="prod{{ $item->product->id }}">
                            <td>
                                <a target="_blank" href="{{ route('products.details', $item->product_id) }}">
                                {{ App::isLocale('en') ? $item->product->title_en :  $item->product->title_ar}}
                                </a>
                            </td>
                            <td>
                                {{ $item->product->final_price . " " . __('messages.usd') }}
                            </td>
                            <td>
                                @if (count($item->serials) > 0)
                                @foreach ($item->serials as $serial)
                                <span class="badge outline-badge-warning">{{ $serial->serial }}</span>
                                @endforeach
                                @endif
                            </td>
                            <td>
                                {{ $item->count }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                
            </div>
        </div>
    </div>  
    
@endsection