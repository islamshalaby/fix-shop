@extends('admin.app')

@section('title' , __('messages.area_details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{ __('messages.area_details') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive"> 
                        <table class="table table-bordered mb-4">
                            <tbody>
                                    <tr>
                                        <td class="label-table" > {{ __('messages.title_en') }}</td>
                                        <td>
                                            {{ $data['area']['title_en'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-table" > {{ __('messages.title_ar') }}</td>
                                        <td>
                                            {{ $data['area']['title_ar'] }}
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td class="label-table" > {{ __('messages.governorate') }}</td>
                                        <td>
                                            <a href="{{ route('areas.governorates.details', $data['area']->governorate_id) }}" target="_blank">
                                                {{ App::isLocale('en') ? $data['area']->governorate->title_en : $data['area']->governorate->title_ar }}
                                            </a>
                                        </td>
                                    </tr>                            
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(isset($data['area']->stores) && count($data['area']->stores) > 0)
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h6>{{ __('messages.delivery_costs') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive"> 
                        <table id="" class="table table-hover non-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>{{ __('messages.store') }}</th>
                                    <th class="text-center">{{ __('messages.delivery_cost') }}</th>
                                    @if(Auth::user()->delete_data) 
                                    <th class="text-center">{{ __('messages.delete') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($data['area']->stores as $store)
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td>{{ $store['name'] }}</td>      
                                        <td  class="text-center">{{ $store['delivery_cost'] . " " . __('messages.dinar') }}</td>
                                        @if(Auth::user()->delete_data) 
                                        <td  class="text-center">
                                            <a onclick='return confirm("{{ __('messages.are_you_sure') }}");' href="{{ route('areas.delivery.delete', $store->d_areas_id) }}" ><i class="far fa-trash-alt"></i></a>
                                        </td>
                                        @endif
                                        <?php $i++; ?>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>  
        </div>
    
@endsection