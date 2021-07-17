@extends('layouts.app_admin')

@section('title', 'HOME')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>HOME</h1>
            </div>
            <div class="col-sm-6">
                {{ Breadcrumbs::render('admin.home') }}
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">

                <div class="card card-purple">
                    <!-- .card-body -->
                    <div class="card-body">
                        <p>{{ Auth::user()->name }} さん。こんにちわ。</p>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>

    </div>
</div>
<!-- /.content -->
@endsection
