@extends('layouts.properties_channels')

@section('content')

{{Form::model($propertieschannel,['action'=>['PropertiesChannelsController@postUpdate',$propertieschannel->channel_id]])}}

{{Form::label('channel_id','Channel:')}}
{{$channel['name']}}
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
{{Form::submit('Update')}}
{{Form::close()}}
@stop