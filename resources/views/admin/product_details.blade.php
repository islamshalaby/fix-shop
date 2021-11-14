@extends('admin.app')

@section('title' , __('messages.product_details'))

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.product_details') }}</h4>
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
                                {{ $data['product']['title_en'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.title_ar') }}</td>
                            <td>
                                {{ $data['product']['title_ar'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.video_link') }}</td>
                            <td>
                                <a href="{{ $data['product']['video'] }}" target="_blank">{{ $data['product']['video'] }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.store') }}</td>
                            <td>
                                {{ $data['product']->store->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.category') }} </td>
                            <td>
                                <a target="_blank" href="{{ route('categories.details', $data['product']['category']['id']) }}">
                                    {{ App::isLocale('en') ? $data['product']['category']['title_en'] : $data['product']['category']['title_ar'] }}
                                </a>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="label-table" > {{ __('messages.description_en') }} </td>
                            <td>
                                {{ $data['product']['description_en'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.total_quatity') }} </td>
                            <td>
                                {{ $data['product']['total_quatity'] }}
                            </td>
                        </tr>
                         <tr>
                            <td class="label-table" > {{ __('messages.remaining_quantity') }} </td>
                            <td>
                                {{ $data['product']['remaining_quantity'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.sold_quantity') }} </td>
                            <td>
                                {{ $data['product']['sold_count'] }}
                            </td>
                        </tr>
                        @if($data['product']['multi_options'] == 0)
                            <tr>
                                <td class="label-table" > {{ __('messages.product_price') }} </td>
                                <td>
                                    {{ $data['product']['final_price'] }} {{ __('messages.dinar') }}
                                </td>
                            </tr>  
                            @if ($data['product']['offer'] == 1)
                            <tr>
                                <td class="label-table" > {{ __('messages.price_before_discount') }} </td>
                                <td>
                                    {{ $data['product']['price_before_offer'] }} {{ __('messages.dinar') }}
                                </td>
                            </tr> 
                            @endif
                        @endif
                        <tr>
                            <td class="label-table" > {{ __('messages.last-update_date') }} </td>
                            <td>
                                {{ $data['product']['updated_at']->format('Y-m-d') }}
                            </td>
                        </tr>             
                    </tbody>
                </table>
                @if ($data['product']['multi_options'] == 1)
                <h5 style="margin-bottom: 20px">{{ __('messages.multi_options') }}</h5>
                <h6>{{ App::isLocale('en') ? $data['product']->mOptions[0]['title_en'] : $data['product']->mOptions[0]['title_ar'] }}</h6>
                
                <div style="margin-bottom: 20px" id="withoutSpacing" class="no-outer-spacing">
                    @foreach($data['product']->multiOptions as $m_option)
                    <div class="card no-outer-spacing">
                        <div class="card-header" id="headingOne2">
                            <section class="mb-0 mt-0">
                                <div role="menu" class="" data-toggle="collapse" data-target="#m-option{{ $m_option->id }}" aria-expanded="true" aria-controls="withoutSpacingAccordionOne">
                                    {{ App::isLocale('en') ? $m_option->multiOptionValue->value_en : $m_option->multiOptionValue->value_ar }}<div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                </div>
                            </section>
                        </div>

                        <div id="m-option{{ $m_option->id }}" class="collapse" aria-labelledby="headingOne2" data-parent="#withoutSpacing">
                            <div class="card-body">
                                <table class="table table-bordered mb-4">
                                    <tbody>
                                        <tr>
                                            <td class="label-table" > {{ __('messages.total_quatity') }}</td>
                                            <td>
                                                {{ $m_option->total_quatity }}
                                            </td>
                                        </tr>   
                                        <tr>
                                            <td class="label-table" > {{ __('messages.remaining_quantity') }}</td>
                                            <td>
                                                {{ $m_option->remaining_quantity }}
                                            </td>
                                        </tr>  
                                        <tr>
                                            <td class="label-table" > {{ __('messages.sold_quantity') }}</td>
                                            <td>
                                                {{ $m_option->sold_count }}
                                            </td>
                                        </tr>  
                                        <tr>
                                            <td class="label-table" > {{ __('messages.product_price') }}</td>
                                            <td>
                                                {{ $m_option->final_price . " " . __('messages.dinar') }}
                                            </td>
                                        </tr>    
                                        @if ($data['product']['offer'] == 1)
                                        <tr>
                                            <td class="label-table" > {{ __('messages.price_before_discount') }}</td>
                                            <td>
                                                {{ $m_option->price_before_offer . " " . __('messages.dinar') }}
                                            </td>
                                        </tr>  
                                        @endif
                                    </tbody>
                                </table> 
                                <div style="text-align:center" >
                                    <br>
                                    <a href="#" data-toggle="modal" data-target="#zoomupModal{{ $m_option->id }}" class="btn btn-{{ $m_option->remaining_quantity == 0 ? 'danger' : 'primary'}}">{{ __('messages.add_quantity') }}</a>
                                </div>
                
                                <div id="zoomupModal{{ $m_option->id }}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ App::isLocale('en') ? $data['product']['title_en'] . " ( " . $m_option->multiOptionValue->value_en . " )"  : $data['product']['title_ar'] . " ( " . $m_option->multiOptionValue->value_ar . " )"}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            
                                            
                                            <div class="modal-body">
                                                <form action="{{ route('option.update.quantity', $m_option->id) }}" method="post" enctype="multipart/form-data" >
                                                    @csrf    
                                                    <div class="form-group mb-4">
                                                        <label for="remaining_quantity">{{ __('messages.quantity') }}</label>
                                                        <input required type="text" name="remaining_quantity" class="form-control" id="remaining_quantity" placeholder="{{ __('messages.quantity') }}" value="" >
                                                    </div>
                                        
                                                    <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @endif
                @if (count($data['product']->properties) > 0)
                <h5>{{ __('messages.properties') }}</h5>
                <table class="table table-bordered mb-4">
                    <tbody>
                        @for ($k = 0; $k < count($data['product']->properties); $k ++)
                        @if(isset($data['product']->values[$k]))
                        <tr>
                            <td class="label-table" > {{ App::isLocale('en') ? $data['product']->properties[$k]['title_en'] : $data['product']->properties[$k]['title_ar'] }}</td>
                            <td>
                                {{ App::isLocale('en') ? $data['product']->values[$k]['value_en'] : $data['product']->values[$k]['value_ar'] }}
                            </td>
                        </tr>
                        @endif
                        @endfor          
                    </tbody>
                </table>
                @endif

                <div class="row">
                    @if (count($data['product']['images']) > 0)
                        @foreach ($data['product']['images'] as $image)
                        <div style="position : relative" class="col-md-2 product_image">
                            <img width="100%" src="https://res.cloudinary.com/dezsm0sg7/image/upload/w_100,q_100/v1581928924/{{ $image->image }}"  />
                        </div>
                        @endforeach
                    @endif
                </div>
                @if(count($data['product']->multiOptions) == 0)
                <div style="text-align:center" >
                    <br>
                    <a href="#" data-toggle="modal" data-target="#zoomupModal{{ $data['product']['id'] }}" class="btn btn-{{ $data['product']['remaining_quantity'] == 0 ? 'danger' : 'primary'}}">{{ __('messages.add_quantity') }}</a>
                </div>

                <div id="zoomupModal{{ $data['product']['id'] }}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ App::isLocale('en') ? $data['product']['title_en'] : $data['product']['title_ar']}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                            
                            
                            <div class="modal-body">
                                <form action="{{ route('update.quantity', $data['product']['id']) }}" method="post" enctype="multipart/form-data" >
                                    @csrf    
                                    <div class="form-group mb-4">
                                        <label for="remaining_quantity">{{ __('messages.quantity') }}</label>
                                        <input required type="text" name="remaining_quantity" class="form-control" id="remaining_quantity" placeholder="{{ __('messages.quantity') }}" value="" >
                                    </div>
                        
                                    <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>  
    
@endsection