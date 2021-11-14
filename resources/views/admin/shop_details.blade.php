@extends('admin.app')

@section('title' , __('messages.request_details'))

@section('content')

        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.store_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.shop_name') }}</td>
                            <td>{{ $data['store']->seller->shop }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.logo') }}</td>
                            <td><img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['store']['logo'] }}"  /></td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.cover') }}</td>
                            <td><img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['store']['cover'] }}"  /></td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.email') }}</td>
                            <td>{{ $data['store']['email'] }}</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <h6>{{ __('messages.personal_data') }}</h6>
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.owner') }}</td>
                            <td>{{ $data['store']->seller->name }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.phone') }}</td>
                            <td>{{ $data['store']->seller->phone }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.id_number') }}</td>
                            <td>{{ $data['store']->seller->id_number }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.instagram') }}</td>
                            <td>{{ $data['store']->seller->instagram }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.account_number') }}</td>
                            <td>{{ $data['store']->seller->account_number }}</td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.front_image') }}</td>
                            <td>
                                <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['store']->seller->front_image }}"  />
                            </td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.back_image') }}</td>
                            <td>
                                <img src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $data['store']->seller->back_image }}"  />
                            </td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.additional_information') }}</td>
                            <td>
                                {{ $data['store']->seller->details }}
                            </td>
                        </tr> 
                        <tr>
                            <td class="label-table" > {{ __('messages.date') }}</td>
                            <td>{{ $data['store']->seller->created_at }}</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>  

@endsection



