@extends('layouts.properties')

@section('content')

{{Form::open(array('action'=>'PropertiesController@postStore'))}}
{{Form::label('name','Property name:')}}
{{Form::text('name')}}
{{Form::label('currency','Currency:')}}
{{Form::text('currency')}}
{{$errors->first('name')}}
{{Form::submit('Add')}}
{{Form::close()}}
@stop