@extends('.layouts.master')

@section('pageContent')


{{ Form::open(array('url'=>'users/login', 'class'=>'form-signin')) }}
    <h2 class="form-signin-heading">Log into Douglas</h2>

<<<<<<< HEAD
    {{ Form::text('username', null, array('class'=>'input-block-level', 'placeholder'=>'User Name')) }}
=======
    {{ Form::text('username', null, array('class'=>'input-block-level', 'placeholder'=>'Username')) }}
>>>>>>> master
    <br />
    {{ Form::password('password', array('class'=>'input-block-level', 'placeholder'=>'Password')) }}
    <br />
    {{ Form::submit('Login', array('class'=>'btn btn-small btn-primary'))}}
{{ Form::close() }}

@stop
