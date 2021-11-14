@extends('admin.app')

@section('title' , __('messages.show_sliders'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_sliders') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="without-print" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>    
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.details') }}</th>  
                            @if(Auth::user()->update_data) 
                                <th class="text-center">{{ __('messages.edit') }}</th>                          
                            @endif                     
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['sliders'] as $slider)
                            <tr>
                                <td><?=$i;?></td>
                                <td>
                                    @if($slider->type == 1)
                                    {{ __('messages.home_page_slider') }}
                                    @elseif($slider->type == 2)
                                    {{ __('messages.category_page_slider') }}
                                    @else
                                    {{ __('messages.offers_slider') }}
                                    @endif
                                </td>
                                <td class="text-center blue-color"><a href="{{ route('sliders.details', $slider->id) }}" ><i class="far fa-eye"></i></a></td>
                                @if(Auth::user()->update_data) 
                                <td class="text-center blue-color" ><a href="{{ route('sliders.edit', $slider->id) }}" ><i class="far fa-edit"></i></a></td>
                                @endif
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