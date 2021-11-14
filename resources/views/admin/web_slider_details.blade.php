@extends('admin.app')

@section('title' , __('messages.slider_details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.slider_details') }} </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.ad') }}</td>
                            <td>
                                <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{ $data->ad->image }}" />
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="label-table" > {{ __('messages.text1_en') }}</td>
                            <td>
                                {{ $data->text1_en }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.text1_ar') }}</td>
                            <td>
                                {{ $data->text1_ar }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.text2_en') }}</td>
                            <td>
                                {{ $data->text2_en }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.text2_ar') }}</td>
                            <td>
                                {{ $data->text2_ar }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.text3_en') }}</td>
                            <td>
                                {{ $data->text3_en }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.text3_ar') }}</td>
                            <td>
                                {{ $data->text3_ar }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.highlighted_text') }} </td>
                            <td>
                                {{ $data->highlighted }}
                            </td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
    
@endsection