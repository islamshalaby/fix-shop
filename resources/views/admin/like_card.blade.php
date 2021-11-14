@extends('admin.app')

@section('title' , __('messages.show_products'))
@push('styles')
<style>
    .filtered-list-search form button {
        left: 4px;
        right : auto
    }
    .add-prod-container {
        padding: 20px 0;
    }
    .widget.box .widget-header,
    .add-prod-container,
    .widget.prod-search {
        background-color: #1b55e2 !important;
    }
    .filtered-list-search {
        margin-top: 50px
    }
    .add-prod-container h4,
    .widget h2{
        color: #FFF
    }
    .filtered-list-search {
        margin-bottom: 0 !important
    }
</style>
@endpush

@section('content')
<div id="badgeCustom" class="col-lg-12 mx-auto layout-spacing">
    <div id="card_2" class="col-lg-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div style="margin-bottom: 50px" class="widget-header">
                
            </div>
            <div class="row">
                <?=$data?>
                

                
            </div>
            
        </div>
    </div>
</div>  

@endsection

@push('scripts')
    <script>
        
    </script>
@endpush