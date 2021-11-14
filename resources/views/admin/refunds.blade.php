@extends('admin.app')

@section('title' , __('messages.show_refund_requests'))

@push('scripts')
<script>
    // fire date form
    $("#toDate").on("change", function() {
        $("#dateForm").submit()
    })
    // fire payment method form
    $("#payment_select").on("change", function() {
        $("#paymentForm").submit()
    })
    // fire shop form
    $("#shop_select").on("change", function() {
        $("#shopForm").submit()
    })
    // fire status form
    $("#status_select").on("change", function() {
        $("#statusForm").submit()
    })
    // fire area form
    $("#area_select").on("change", function() {
        $("#areaForm").submit()
    })
</script>
<script>
    var sumPrice = "{{ $data['sum_price'] }}",
        priceString = "{{ __('messages.price') }}",
        dinar = "{{ __('messages.dinar') }}"
    var dTbls = $('#order-tbl').DataTable( {
        dom: 'Blfrtip',
        buttons: {
            buttons: [
                { extend: 'copy', className: 'btn', footer: true, exportOptions: {
                    columns: ':visible',
                    rows: ':visible'
                }},
                { extend: 'csv', className: 'btn', footer: true, exportOptions: {
                    columns: ':visible',
                    rows: ':visible'
                } },
                { extend: 'excel', className: 'btn', footer: true, exportOptions: {
                    columns: ':visible',
                    rows: ':visible'
                } },
                { extend: 'print', className: 'btn', footer: true, 
                    exportOptions: {
                        columns: ':visible',
                        rows: ':visible'
                    },customize: function(win) {
                        $(win.document.body).prepend(`<br /><h4 style="border-bottom: 1px solid; padding : 10px">${priceString} : ${sumPrice} ${dinar} </h4>`); //before the table
                      }
                }
            ]
        },
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
           "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [50, 100, 1000, 10000, 100000, 1000000, 2000000, 3000000, 4000000, 5000000],
        "pageLength": 50  
    } );
</script>
<script>
    var total = dTbls.column(7).data(),
        dinar = "{{ __('messages.dinar') }}"
    var allTotal = parseFloat(total.reduce(function (a, b) { return parseFloat(a) + parseFloat(b); }, 0)).toFixed(3)

    $("#order-tbl tfoot").find('th').eq(7).text(`${allTotal} ${dinar}`);
</script>
@endpush

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="form-group col-md-6">
                        <form id="dateForm" method="" action="">
                            <div class="form-group mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="from">{{ __('messages.from') }}</label>
                                        <input required type="date" name="from" class="form-control" value="{{ isset($data['from']) ? $data['from'] : '' }}" id="from" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="toDate">{{ __('messages.to') }}</label>
                                        <input required type="date" name="to" value="{{ isset($data['to']) ? $data['to'] : '' }}" class="form-control" id="toDate" >
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <div class="form-group col-md-3">
                        <form id="paymentForm" method="" action="">
                            
                            <label for="payment_select">{{ __('messages.payment_method') }}</label>
                            <select required id="payment_select" name="method" class="form-control">
                                <option disabled selected>{{ __('messages.select') }}</option>
                                
                                <option {{ isset($data['method']) && $data['method'] == 1 ? 'selected' : '' }} value="1">{{ __('messages.key_net') }}</option>
                                <option {{ isset($data['method']) && $data['method'] == 2 ? 'selected' : '' }} value="2">{{ __('messages.cash') }}</option>
                                <option {{ isset($data['method']) && $data['method'] == 3 ? 'selected' : '' }} value="3">{{ __('messages.wallet') }}</option>
                                
                            </select>
                                
                        </form>
                    </div>
                    <div class="form-group col-md-3">
                        <form id="shopForm" method="" action="">
                            
                            <label for="payment_select">{{ __('messages.store') }}</label>
                            <select required id="shop_select" name="shop" class="form-control">
                                <option disabled selected>{{ __('messages.select') }}</option>
                                @foreach ($data['shops'] as $shop)
                                <option {{ isset($data['shop']) && $data['shop'] == $shop->id ? 'selected' : '' }} value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                                
                            </select>
                                
                        </form>
                    </div>
                    <div class="form-group col-md-3">
                        <form id="statusForm" method="" action="">
                            
                            <label for="status_select">{{ __('messages.status') }}</label>
                            <select required id="status_select" name="status" class="form-control">
                                <option disabled selected>{{ __('messages.select') }}</option>
                                
                                <option {{ isset($data['status']) && $data['status'] == 5 ? 'selected' : '' }} value="5">{{ __('messages.refund_request') }}</option>
                                <option {{ isset($data['status']) && $data['status'] == 6 ? 'selected' : '' }} value="6">{{ __('messages.refund_accepted') }}</option>
                                <option {{ isset($data['status']) && $data['status'] == 7 ? 'selected' : '' }} value="7">{{ __('messages.refund_rejected') }}</option>
                                <option {{ isset($data['status']) && $data['status'] == 8 ? 'selected' : '' }} value="8">{{ __('messages.received_refund') }}</option>
                                
                            </select>
                                
                        </form>
                    </div>
                    <div class="form-group col-md-3">
                        <form id="areaForm" method="" action="">
                            
                            <label for="area_select">{{ __('messages.area') }}</label>
                            <select required id="area_select" name="area" class="form-control">
                                <option disabled selected>{{ __('messages.select') }}</option>
                                @foreach ($data['areas'] as $area)
                                <option {{ isset($data['area']) && $data['area'] == $area->id ? 'selected' : '' }} value="{{ $area->id }}">{{ App::isLocale('en') ? $area->title_en : $area->title_ar }}</option>
                                @endforeach
                                
                            </select>
                                
                        </form>
                    </div>
                </div>
            </div>
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_refund_requests') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="order-tbl" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>  
                            <th>{{ __('messages.refund_number') }}</th>  
                            <th>{{ __('messages.main_order_number') }}</th> 
                            <th>{{ __('messages.sub_order_number') }}</th> 
                            <th>{{ __('messages.user_name') }}</th>
                            <th>{{ __('messages.store') }}</th>
                            <th>{{ __('messages.product_title') }}</th>
                            <th>{{ __('messages.product_price') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th class="text-center">{{ __('messages.details') }}</th>
                            <th class="text-center">{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['refunds'] as $item)
                            <tr class="{{$item->admin_seen == 0 ? 'unread' : '' }}" >
                                <td><?=$i;?></td>
                                <td>{{ $item->refund_number }}</td>
                                <td>
                                    <a href="{{ route('orders.details', $item->item->order->main_id) }}" target="_blank">
                                        {{ $item->item->order->main->main_order_number }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('orders.sub_order.details', $item->item->order_id) }}" target="_blank">
                                        {{ $item->item->order->order_number }}
                                    </a>
                                </td>
                                
                                <td>
                                    <a href="{{ route('users.details', $item->user_id) }}" target="_blank">
                                    {{ $item->user->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('shops.details', $item->store_id) }}" target="_blank">
                                        {{ $item->store->name }}
                                    </a>
                                </td>
                                <td>{{ App::isLocale('en') ? $item->item->product->title_en : $item->item->product->title_ar }}</td>
                                <td>{{ $item->item->final_price . " " . __('messages.dinar') }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td class="text-center blue-color"><a href="{{ route('refund.details', $item->id) }}" ><i class="far fa-eye"></i></a></td>
                                <td style="font-weight : bold" class="text-center blue-color" >
                                    @if($item->item->status == 6)
                                    {{ __('messages.refund_accepted') }}
                                    @elseif($item->item->status == 7)
                                    {{ __('messages.refund_rejected') }}
                                    @elseif($item->item->status == 5)
                                    {{ __('messages.refund_request') }}
                                    @else
                                    {{ __('messages.received_refund') }}
                                    @endif
                                </td>
                                
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                          <th>{{ __('messages.total') }}:</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        {{-- <div class="paginating-container pagination-solid">
            <ul class="pagination">
                <li class="prev"><a href="{{$data['contact_us']->previousPageUrl()}}">Prev</a></li>
                @for($i = 1 ; $i <= $data['contact_us']->lastPage(); $i++ )
                    <li class="{{ $data['contact_us']->currentPage() == $i ? "active" : '' }}"><a href="/admin-panel/contact_us/?page={{$i}}">{{$i}}</a></li>               
                @endfor
                <li class="next"><a href="{{$data['contact_us']->nextPageUrl()}}">Next</a></li>
            </ul>
        </div>   --}}
        
    </div>  

@endsection