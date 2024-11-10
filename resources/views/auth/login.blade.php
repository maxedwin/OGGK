@extends('layouts.login_layout')
@section('contenido')
<!-- BEGIN LOGIN FORM -->
<div class="panel panel-body login-form">
    <div class="text-center">
        <div class="icon-object border-slate-300 text-slate-300"><img src="/images/logo.jpg" data-holder-rendered="true" style="width:70%;height:auto;" /></div>
        <h5 class="content-group">Ingresa a tu cuenta. <small class="display-block">Tus Credenciales</small></h5>
    </div>
    <form method="post" action="{{ url('/login') }}">
        {!! csrf_field() !!}

        @if ($errors->has('email'))
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> Ingresar usuario y password </span>
            
        </div>
        @endif
        <!--<div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>-->

        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} has-feedback has-feedback-left">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label">Email</label>
            <input class="form-control " type="text" autocomplete="off" placeholder="user@mail.com" name="email" value="{{ old('email') }}" />
            @if ($errors->has('email'))
                                    <span >
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
            
            @if (session('status'))
                        <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        
            @endif
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback has-feedback-left">
            <label class="control-label">Password</label>
            <input class="form-control " type="password" autocomplete="off" placeholder="password" name="password"/>
            @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
           @endif
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>


        <div class="form-actions">
            <button type="submit" class="btn btn bg-blue btn-block uppercase">Ingresar<i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>

</div>

@stop