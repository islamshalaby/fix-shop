@extends('admin.app')

@section('title' , __('messages.slider_details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.slider_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        @foreach ($data['slider'] as $slider)
                        <tr>
                            <td class="label-table" > {{ __('messages.image') }}</td>
                            <td>
                                <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $slider['image'] }}"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.ad_place') }}</td>
                            <td>
                                {{ $slider['type'] == 2 ? __('messages.outside_the_app') : __('messages.inside_the_app') }}
                            </td>
                        </tr>
                        @if ($slider['type'] == 2)
                        <tr>
                            <td class="label-table" > {{ __('messages.link') }} </td>
                            <td>
                                <a target="_blank" href="{{ $slider['content'] }}" >
                                    {{ $slider['content'] }}
                                </a>
                            </td>
                        </tr>    
                        @else
                            @if ($slider['content_type'] == 1)
                            <tr>
                                <td class="label-table" > {{ __('messages.product') }} </td>
                                <td>
                                    {{ App::isLocale('en') && isset($slider->product->id) ? $slider->product->title_en : $slider->product->title_ar }}
                                </td>
                            </tr> 
                            @endif
                            @if ($slider['content_type'] == 2)
                            <tr>
                                <td class="label-table" > {{ __('messages.category') }} </td>
                                <td>
                                    {{ App::isLocale('en') && isset($slider->category->id) ? $slider->category->title_en : $slider->category->title_ar }}
                                </td>
                            </tr> 
                            @endif
                            @if ($slider['content_type'] == 3)
                            <tr>
                                <td class="label-table" > {{ __('messages.store') }} </td>
                                <td>
                                    {{ isset($slider->store->id) ? $slider->store->name : '' }}
                                </td>
                            </tr> 
                            @endif
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
    
@endsection