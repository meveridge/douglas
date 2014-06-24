@extends('.layouts.master')

@section('pageContent')


{{ Form::open(array('url'=>'users/login', 'class'=>'form-signin')) }}
    <h2 class="form-signin-heading">Log into Douglas</h2>

    {{ Form::text('username', null, array('class'=>'input-block-level', 'placeholder'=>'User Name')) }}
    <br />
    {{ Form::password('password', array('class'=>'input-block-level', 'placeholder'=>'Password')) }}
    <br />
    {{ Form::submit('Login', array('class'=>'btn btn-small btn-primary'))}}
{{ Form::close() }}

@stop
