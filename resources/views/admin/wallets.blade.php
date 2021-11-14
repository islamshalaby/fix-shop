@extends('admin.app')

@section('title' , __('messages.show_wallets'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_wallets') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>  
                            <th>{{ __('messages.user_name') }}</th>  
                            <th>{{ __('messages.wallet_balance') }}</th> 
                            <th>{{ __('messages.transactions') }}</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['wallets'] as $wallet)
                            <tr>
                                <td><?=$i;?></td>
                                <td><a target="_blank" href="/admin-panel/users/details/{{ $wallet->user->id }}" >{{ $wallet->user->name }}</a></td>
                                <td>{{ $wallet->balance . " " . __('messages.dinar') }}</td>
                                <td class="text-center blue-color text-center hide_col"><a href="{{ route('wallet.transactions', $wallet->id) }}" ><i class="far fa-eye"></i></a></td>
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                    </tbody>
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