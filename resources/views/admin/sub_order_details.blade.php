@extends('admin.app')

@section('title' , __('messages.sub_order_details'))

@push('scripts')
    <script>
        $(".statusSelect").on("change", function() {
            $(this).parent('form').submit()
        })
        $(".itemStatusSelect").on("change", function() {
            $(this).parent('form').submit()
        })
    </script>
@endpush

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.sub_order_details') }} </h4>
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
                                <a target="_blank" href="{{ route('orders.details', $data['order']->main_id) }}">
                                    {{ $data['order']->main->main_order_number }}
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
                                @if($data['order']->payment_method == 1)
                                {{ __('messages.key_net') }}
                                @elseif ($data['order']->payment_method == 2)
                                {{ __('messages.cash') }}
                                @else
                                {{ __('messages.wallet') }}
                                @endif
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.status') }} </td>
                            <td>
                                @if (in_array($data['order']->status, [1, 2, 5]))
                                {{ __('messages.opened') }}
                                @else
                                {{ __('messages.closed') }}
                                @endif
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.price') }} </td>
                            <td>
                                {{ $data['order']['subtotal_price'] . " " . __('messages.dinar') }}
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.delivery_cost') }} </td>
                            <td>
                                {{ $data['order']['delivery_cost'] . " " . __('messages.dinar') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.total') }} </td>
                            <td>
                                {{ $data['order']['total_price'] . " " . __('messages.dinar') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.address') }} </td>
                            <td>
                                <a style="text-decoration: none" href="https://www.google.com/maps/?q={{ $data['order']->address->latitude }},{{ $data['order']->address->longitude }}" target="_blank"> {{ $data['order']->address->area->title_en . ", " . __('messages.st') . " " . $data['order']->address->street . ", " . __('messages.piece') . " " . $data['order']->address->piece . ", " . __('messages.gaddah') . " " . $data['order']->address->gaddah  }} <br/> {{ __('messages.home') . " " . $data['order']->address->building . ', ' . __('messages.floor') . " "  . $data['order']->address->floor . ', ' . __('messages.apartment') . " " . $data['order']->address->apartment_number }}</a>
                            </td>
                        </tr>
                       
                    </tbody>
                </table>
                <h5 style="margin-bottom : 20px">
                    <a target="_blank" href="{{ route('shops.details', $data['order']->store_id) }}">
                    {{ $data['order']->store->name }}
                    </a>
                </h5>
                <p><b>{{ __('messages.sub_order_number') }} :</b> {{ $data['order']->order_number }}
                    @if (!in_array($data['order']->status, [3, 4, 9]))
                    <a style="margin-bottom: 5px" href="{{ route('orders.cancel', ['order', $data['order']->id]) }}" onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-sm btn-danger hide_col">
                        {{ __('messages.cancel_order') }}
                    </a>
                    @endif
                    {{--  @if( !in_array($data['order']->status, [3, 4, 9])) 
                    <form action="{{ route('orders.subo.action', $data['order']->id) }}" >
                        <select id="statusSelect" name="status" class="form-control statusSelect">
                            <option selected>{{ __('messages.select') }}</option>
                            <option {{ $data['order']->status == 1 ? 'selected' : '' }} value="1">{{ __('messages.in_progress') }}</option>
                            <option {{ $data['order']->status == 2 ? 'selected' : '' }} value="2">{{ __('messages.order_confirmed') }}</option>
                            <option {{ $data['order']->status == 3 ? 'selected' : '' }} value="3">{{ __('messages.delivered') }}</option>
                        </select>
                    </form>
                    @endif  --}}
                </p>
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ __('messages.product_price') }}</th>
                            <th>{{ __('messages.count') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-center">{{ __('messages.actions') }}</th>
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
                                {{ $item->product->final_price . " " . __('messages.dinar') }}
                            </td>
                            <td>
                                {{ $item->count }}
                            </td>
                            <td>
                                {{-- <form action="{{ route('orders.items.action', $item->id) }}" >
                                    <select id="itemStatusSelect" name="status" class="form-control">
                                        <option selected>{{ __('messages.select') }}</option>
                                        <option {{ $item->status == 1 ? 'selected' : '' }} value="1">{{ __('messages.in_progress') }}</option>
                                        <option {{ $item->status == 2 ? 'selected' : '' }} value="2">{{ __('messages.order_confirmed') }}</option>
                                        <option {{ $item->status == 3 ? 'selected' : '' }} value="3">{{ __('messages.delivered') }}</option>
                                    </select>
                                </form> --}}
                                @if ($item->status == 1)
                                {{ __('messages.in_progress') }}
                                @elseif($item->status == 2)
                                {{ __('messages.order_confirmed') }}
                                @elseif($item->status == 3)
                                {{ __('messages.delivered') }}
                                @elseif($item->status == 4)
                                {{ __('messages.canceled_from_user') }}
                                @elseif($item->status == 5)
                                <a href="{{ route('refund.details', $item->refund->id) }}" target="_blank">
                                    {{ __('messages.refund_request') }}
                                </a>
                                @elseif($item->status == 6)
                                {{ __('messages.refund_accepted') }}
                                @elseif($item->status == 7)
                                {{ __('messages.refund_rejected') }}
                                @elseif($item->status == 8)
                                {{ __('messages.received_refund') }}
                                @elseif($item->status == 9)
                                {{ __('messages.canceled_from_admin') }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if( $item->status == 5 )
                                <a onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-danger" href="{{ route('refund.accept', $item->refund->id) }}">{{ __('messages.accept_refund') }}</a>
                                <a onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-primary" href="{{ route('refund.reject', $item->refund->id) }}">{{ __('messages.reject_refund') }}</a>
                                @endif
                                @if($item->status == 6)
                                <a onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-primary" href="{{ route('refund.received', $item->refund->id) }}">{{ __('messages.received_refund') }}</a>
                                @endif
                                @if (!in_array($item->status, [3, 4, 9]))
                                <a style="margin-bottom: 5px" href="{{ route('orders.cancel', ['item', $item->id]) }}" onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-sm btn-danger hide_col">
                                    {{ __('messages.cancel_order') }}
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>  
    
@endsection