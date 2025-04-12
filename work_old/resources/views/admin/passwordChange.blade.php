@extends('layouts.app_admin')

@section('title', __('common.Password Change'))

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.passwordChange') }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.home') }}">{{ __('common.Back')
        }}</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.passwordChange.update') }}">
        @method('PUT')
        @csrf
        <div class="card card-purple">
            <div class="card-body">
               <div class="form-group">
                   <div class="form-group">
                       <div class="control-group" id="password">
                           <label class="col-sm-6 control-label">{{ __('staff.Password') }}</label>
                           <div class="col-sm-12">
                               <input
                                   type="password"
                                   name="password"
                                   value=""
                                   class="form-control"
                                   maxlength="{{ config('const.maxlength.staffs.password') }}"
                               />
                           </div>
                       </div>
                   </div>
                    <div class="control-group" id="password">
                        <label class="col-sm-6 control-label">{{ __('staff.Password') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="password"
                                name="password_confirmation"
                                value=""
                                class="form-control"
                                maxlength="{{ config('const.maxlength.staffs.password') }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center  ">
                <button
                    class="btn btn-info"
                    type="submit"
                >
                    {{ __('common.Change') }}
                </button>
            </div>
        </div>
    </form>

@endsection
