@extends('layouts.rooms')

@section('content')

{{Form::open(array('action'=>'RoomsController@postStore'))}}
{{Form::label('name','Room name:')}}
{{Form::text('name')}}
{{$errors->first('name')}}
<br>
{{Form::label('rack_rate','Rack rate:')}}
{{Form::text('rack_rate')}}
{{$errors->first('rack_rate')}}
<br>
{{Form::submit('Add room')}}
{{Form::close()}}
@stop