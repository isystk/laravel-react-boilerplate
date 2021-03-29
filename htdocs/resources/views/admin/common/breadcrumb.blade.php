
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    @foreach($breadcrumbs as $breadcrumb)
                      @isset ($breadcrumb -> link)
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb -> link }}">{{$breadcrumb -> label}}</a></li>
                      @else
                        <li class="breadcrumb-item {{ $loop->last ? 'active' : ''}}">{{$breadcrumb -> label}}</li>
                      @endif
                    @endforeach
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
