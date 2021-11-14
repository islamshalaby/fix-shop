@extends('admin.app')
@section('title' , __('messages.show_countries'))
@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">

        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_countries') }}</h4>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <a class="btn btn-primary" href="/admin-panel/categories/add">{{ __('messages.add') }}</a>
                </div>
            </div> --}}
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">{{ __('messages.country_name') }}</th>
                            <th class="text-center">{{ __('messages.currency') }}</th>
                            <th class="text-center">Iso Code</th>
                            @if(Auth::user()->update_data)<th class="text-center">{{ __('messages.edit') }}</th>@endif
                            @if(Auth::user()->delete_data)<th class="text-center">{{ __('messages.delete') }}</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['countries'] as $country)
                            <tr>
                                <td><?=$i;?></td>
                                <td class="text-center">{{ $country->country_name }}</td>
                                <td class="text-center">{{ app()->getLocale() == 'en' ? $country->currency_en : $country->currency_ar }}</td>
                                <td class="text-center">
                                {{ $country->country_code }}
                                </td>
                                @if(Auth::user()->update_data)
                                    <td class="text-center blue-color" ><a href="{{ route('countries.edit', $country->id) }}" ><i class="far fa-edit"></i></a></td>
                                @endif
                                @if(Auth::user()->delete_data)
                                    <td class="text-center blue-color" ><a onclick="return confirm('Are you sure you want to delete this item?');" href="#" ><i class="far fa-trash-alt"></i></a></td>
                                @endif
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
