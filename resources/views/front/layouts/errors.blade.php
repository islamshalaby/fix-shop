
@if(Session::has('errors'))
    <div style="margin-top: 20px" class="alert alert-danger">
        @foreach ($errors->all() as  $value)

            <p>{{ $value }}</p>

    @endforeach
    <!-- <p>{{ Session('errors') }}</p> -->
    </div>
@endif

@if(Session::has('danger'))
    <div style="margin-top: 20px" class="alert alert-danger">
        <p>{{ Session('danger') }}</p>
    </div>
@endif

@if(Session::has('danger_deactive'))
    <div style="margin-top: 20px" class="alert alert-danger">
        <p>{{ Session('danger_deactive') }}</p>
        <a target="_blank" href="http://land.golden-info.com/index.html#tm-area-contact">تواصل مع خدمه العملاء </a>
    </div>
@endif


@if(session('success'))
    <div style="margin-top: 20px" class="alert alert-success" role='alert'>
        {{session('success')}}
    </div>
@endif
