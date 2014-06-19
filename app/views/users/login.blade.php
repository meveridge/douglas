@extends('.layouts.master')

@section('pageContent')


{{ Form::open(array('url'=>'users/signin', 'class'=>'form-signin')) }}
    <h2 class="form-signin-heading">Log into Douglas</h2>

    {{ Form::text('email', null, array('class'=>'input-block-level', 'placeholder'=>'Email Address')) }}
    <br />
    {{ Form::password('password', array('class'=>'input-block-level', 'placeholder'=>'Password')) }}
    <br />
    {{ Form::submit('Login', array('class'=>'btn btn-small btn-primary'))}}
{{ Form::close() }}

@stop
