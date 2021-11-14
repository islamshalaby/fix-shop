@extends('shop.app')

@section('title' , __('messages.order_details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.order_details') }} 
                        @if ($data['order']['status'] == 2)
                            ( <a style="color: #1b55e2" target="_blank" href="{{ route('orders.store.invoice', $data['order']['id']) }}">
                                {{ __('messages.invoice') }}
                            </a> )
                        @endif
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
                                {{ $data['order']->main->main_order_number }}
                            </td>
                        </tr>
                        @if ($data['order']['status'] == 2)
                        <tr>
                            <td class="label-table" > {{ __('messages.invoice') }}</td>
                            <td>
                                <a href="{{ route('orders.store.invoice', $data['order']['id']) }}">
                                    {{ __('messages.invoice') }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td class="label-table" > {{ __('messages.order_date') }}</td>
                            <td>
                                {{ $data['order']->main->created_at->format("Y-m-d") }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.user') }} </td>
                            <td>
                                {{ $data['order']->user->name }}
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.payment_method') }} </td>
                            <td>
                                @if($data['order']->payment_method == 1)
                                    {{ __('messages.key_net') }}
                                    @elseif ($data['order']->payment_method == 2)
                                    {{ __('messages.key_net_from_home') }}
                                    @else
                                    {{ __('messages.cash') }}
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
                    {{ $data['order']->store->name }}
                </h5>
                <p><b>{{ __('messages.sub_order_number') }} :</b> {{ $data['order']->order_number }}</p>
                <p>{{ __('messages.from') . " " . $data['order']->from_deliver_date->format('d-m-y') . " " . __('messages.to') . " " . $data['order']->to_deliver_date->format('d-m-y') }}</p>
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ App::isLocale('en') ? $data['m_option']['title_en'] : $data['m_option']['title_ar'] }}</th>
                            <th>{{ __('messages.count') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['order']->oItems as $item)
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('products.store.details', $item->product_id) }}">
                                {{ App::isLocale('en') ? $item->product->title_en :  $item->product->title_ar}}
                                </a>
                            </td>
                            <td>
                                @if($item->option_id != 0)
                                {{ $item->product->mOptionsWhere($item->option_id)->multiOptionValue->value_en }}
                                @else
                                <a data-toggle="modal" data-target="#zoomupModal{{ $item->id }}" href="#" target="_blank">{{ __('messages.details') }}</a>
                                <div id="zoomupModal{{ $item->id }}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $item->product->title_en }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            
                                            
                                            <div class="modal-body">
                                                <div class="widget-content widget-content-area">
                                                    <div class="table-responsive"> 
                                                        <table class="table table-bordered mb-4">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.length') }}</td>
                                                                    <td>
                                                                        {{ $item->size->tall }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.shoulder') }}</td>
                                                                    <td>
                                                                        {{ $item->size->shoulder_width }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.chest') }}</td>
                                                                    <td>
                                                                        {{ $item->size->chest }}
                                                                    </td>
                                                                </tr>     
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.waist') }}</td>
                                                                    <td>
                                                                        {{ $item->size->waist }}
                                                                    </td>
                                                                </tr>  
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.buttock') }}</td>
                                                                    <td>
                                                                        {{ $item->size->buttocks }}
                                                                    </td>
                                                                </tr>  
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.sleeve') }}</td>
                                                                    <td>
                                                                        {{ $item->size->sleeve }}
                                                                    </td>
                                                                </tr>  
                                                                <tr>
                                                                    <td class="label-table" > {{ __('messages.additional_details') }}</td>
                                                                    <td>
                                                                        {{ $item->size->details }}
                                                                    </td>
                                                                </tr>                    
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td>
                                {{ $item->count }}
                            </td>
                            <td>
                                @if ($item->status == 1)
                                {{ __('messages.in_progress') }}
                                @elseif($item->status == 2)
                                {{ __('messages.delivered') }}
                                @elseif($item->status == 3)
                                {{ __('messages.retrieved') }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 1)
                                <a href="{{ route('orders.store.items.action', [$item->id, 3]) }}" onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-sm btn-danger hide_col">
                                    {{ __('messages.retrieve') }}
                                </a>
                                <a href="{{ route('orders.store.items.action', [$item->id, 2]) }}" onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-sm btn-success hide_col">
                                    {{ __('messages.order_delivered') }}
                                </a>
                                @endif
                                @if($item->status == 2)
                                <a href="{{ route('orders.store.items.action', [$item->id, 3]) }}" onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-sm btn-danger hide_col">
                                    {{ __('messages.retrieve') }}
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