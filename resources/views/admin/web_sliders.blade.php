@extends('admin.app')

@section('title' , __('messages.web_sliders'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.web_sliders') }} 
                        @if(Auth::user()->add_data)
                        <a href="{{ route('sliders.web.add') }}" class="btn btn-primary">{{ __('messages.add') }}</a>
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>  
                            <th>{{ __('messages.image') }}</th>  
                            <th class="text-center hide_col">{{ __('messages.details') }}</th>
                            @if(Auth::user()->update_data) 
                                <th class="text-center hide_col">{{ __('messages.edit') }}</th>
                            @endif 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $row)
                            <tr>
                                <td><?=$i;?></td>
                                <td>
                                    <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $row->ad ? $row->ad->image : '' }}" />
                                </td>
                                <td class="text-center blue-color text-center hide_col"><a href="{{ route('sliders.web.details', $row->id) }}" ><i class="far fa-eye"></i></a></td>
                                @if(Auth::user()->update_data) 
                                    <td class="text-center blue-color text-center hide_col" ><a href="{{ route('sliders.web.edit', $row->id) }}" ><i class="far fa-edit"></i></a></td>
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