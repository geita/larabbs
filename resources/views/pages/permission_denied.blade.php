@extends('layouts.app')
@section('title', '无权限访问')

@section('content')

    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-body">
                @if (Auth::check())
                    <div class="alert alert-danger text-center">
                        当前登陆账号吴后台访问权限
                    </div>
                @else
                    <div class="alert alert-danger text-center">
                        请登陆后再操作
                    </div>
                    <a class="btn btn-lg btn-primary btn-block" href="{{ route('login') }}">
                    <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                    登 陆
                </a>
                @endif
            </div>
        </div>
    </div>

@stop