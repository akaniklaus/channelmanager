@extends('layouts.properties_channels')

@section('content')

{{Form::open(array('action'=>'PropertiesChannelsController@postStore'))}}
{{Form::label('channel_id','Channel:')}}
{{Form::select('channel_id',$channels)}}
{{$errors->first('channel_id')}}
<br>

{{Form::label('login','Login:')}}
{{Form::text('login')}}
{{$errors->first('login')}}
<br>
{{Form::label('password','Password:')}}
{{Form::text('password')}}
{{$errors->first('password')}}
<br>
{{Form::label('hotel_code','Hotel Code:')}}
{{Form::text('hotel_code')}}
{{$errors->first('hotel_code')}}
<br>
{{Form::submit('Add')}}
{{Form::close()}}
@stop