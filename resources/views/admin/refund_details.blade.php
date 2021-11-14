@extends('admin.app')

@section('title' , __('messages.refund_request_details'))

@section('content')

        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.refund_request_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.main_order_number') }}</td>
                            <td><a target="_blank" href="{{ route('orders.details', $data['refund']->item->order->main->id) }}"> {{ $data['refund']->item->order->main->main_order_number }}</a></td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.sub_order_number') }}</td>
                            <td>{{ $data['refund']->item->order->order_number }}</td>
                        </tr>
                        
                        <tr>
                            <td class="label-table" > {{ __('messages.user_name') }}</td>
                            <td>{{ $data['refund']->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.store') }}</td>
                            <td>{{ $data['refund']->store->name }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.reason') }}</td>
                            <td>{{ $data['refund']['reason'] }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.date') }}</td>
                            <td>{{ $data['refund']['created_at'] }}</td>
                        </tr> 
                    </tbody>
                </table>
                
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        {{ __('messages.product_details') }} 
                        @if($data['refund']->item->status == 5)
                        <a class="btn btn-danger"  onclick='return confirm("{{ __('messages.are_you_sure') }}");' href="{{ route('refund.accept', $data['refund']->id) }}">{{ __('messages.accept_refund') }}</a>
                        <a onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-primary" href="{{ route('refund.reject', $data['refund']->id) }}">{{ __('messages.reject_refund') }}</a>
                        @endif
                        @if($data['refund']->item->status == 6)
                        <a onclick='return confirm("{{ __('messages.are_you_sure') }}");' class="btn btn-primary" href="{{ route('refund.received', $data['refund']->id) }}">{{ __('messages.received_refund') }}</a>
                        @endif
                    </h4>
                </div>
            </div>
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.product') }}</td>
                            <td><a target="_blank" href="{{ route('orders.details', $data['refund']->item->order->main->id) . '#prod' . $data['refund']->item->product->id }}">{{ App::isLocale('en') ? $data['refund']->item->product->title_en : $data['refund']->item->product->title_ar }}</a></td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.count') }}</td>
                            <td>{{ $data['refund']->item->count }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.date') }}</td>
                            <td>{{ $data['refund']->item->created_at }}</td>
                        </tr> 
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>  

@endsection



