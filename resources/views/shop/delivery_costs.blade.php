@extends('shop.app')

@section('title' , __('messages.show_delivery_costs'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_delivery_costs') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="without-print" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ __('messages.area_title') }}</th>
                            <th>{{ __('messages.delivery_cost') }}</th>
                            <th class="text-center">{{ __('messages.edit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['costs'] as $cost)
                            <tr>
                                <td><?=$i;?></td>
                                <td>{{ App::isLocale('en') ? $cost->title_en : $cost->title_ar }}</td>
                                <td>{{ $cost->delivery_cost . " " . __('messages.dinar') }}</td> 
                                <td class="text-center blue-color" ><a href="{{ route('delivery_costs.store.edit', $cost->costId) }}" ><i class="far fa-edit"></i></a></td>               
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                    </tbody>
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