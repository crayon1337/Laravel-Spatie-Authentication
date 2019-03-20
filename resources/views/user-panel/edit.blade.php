@extends('layouts.app')
@section('title', '| User-Panel')
@section('content')
<div class="card">
    <div class="card-header">
        User Panel
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            <li><strong>Name:</strong> {{ $user->name }} </li>
            <li><strong>EMail:</strong> {{ $user->email }}</li>
            @if(is_null($role))
                <li><strong>Role:</strong> {{  $user->roles()->pluck('name')->implode(' ') }}</li>
                <li><strong>Permissions:</strong> {{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</li>
            @endif
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Change Password
    </div>
    <div class="card-body">
        {{ Form::model($user, array('route' => array('user-panel.update', $user->id), 'method' => 'PATCH')) }}
        <div class="form-group">
            {{ Form::label('password', 'Current Password') }}
            {{ Form::password('current_password', array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', 'New Password') }}
            {{ Form::password('password', array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', 'Password Confirmation') }}
            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
        </div>
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
