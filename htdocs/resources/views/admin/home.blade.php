@extends('layouts.app_admin')

@section('title', 'HOME')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>HOME</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

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
