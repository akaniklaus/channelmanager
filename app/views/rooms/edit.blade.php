@extends('layouts.rooms')

@section('content')

{{Form::model($room,['action'=>['RoomsController@postUpdate',$room->id]])}}
{{Form::label('name','Room name:')}}
{{Form::text('name')}}
{{$errors->first('name')}}
<br>
{{Form::label('rack_rate','Rack rate:')}}
{{Form::text('rack_rate')}}
{{$errors->first('rack_rate')}}
<br>
{{Form::label('property_id','Property ID:')}}
{{$room->property_id}}
<br>
{{Form::submit('Update room')}}
@stop