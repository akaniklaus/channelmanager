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

{{Form::label('type','Type:')}}
<label>{{Form::radio('type','room',true)}} room</label>
<label>{{Form::radio('type','plan')}} plan</label>
{{$errors->first('type')}}
<br/>
{{Form::label('parent_id','Parent Room:')}}
{{Form::select('parent_id',[null=>'']+$rooms)}}
{{$errors->first('parent_id')}}
<br/>
{{Form::label('formula_type','Rate Formula:')}}
{{Form::select('formula_type',[null=>'']+$formulaTypes)}}{{Form::text('formula_value')}}
{{$errors->first('formula_type')}}
{{$errors->first('formula_value')}}
<br/>

{{Form::submit('Update room')}}
{{Form::close()}}
@stop