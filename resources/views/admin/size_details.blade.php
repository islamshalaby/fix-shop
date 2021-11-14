@extends('admin.app')

@section('title' , __('messages.details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.length') }}</td>
                            <td>
                                {{ $data['size']['tall'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.shoulder') }}</td>
                            <td>
                                {{ $data['size']['shoulder_width'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.chest') }}</td>
                            <td>
                                {{ $data['size']['chest'] }}
                            </td>
                        </tr>     
                        <tr>
                            <td class="label-table" > {{ __('messages.waist') }}</td>
                            <td>
                                {{ $data['size']['waist'] }}
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.buttock') }}</td>
                            <td>
                                {{ $data['size']['buttocks'] }}
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.sleeve') }}</td>
                            <td>
                                {{ $data['size']['sleeve'] }}
                            </td>
                        </tr>  
                        <tr>
                            <td class="label-table" > {{ __('messages.additional_details') }}</td>
                            <td>
                                {{ $data['size']['details'] }}
                            </td>
                        </tr>                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
    
@endsection