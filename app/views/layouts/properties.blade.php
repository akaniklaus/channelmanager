@extends('layouts.master')

@section('menu')
<li><a href="{{URL::action('PropertiesController@getCreate')}}">Add Property</a></li>
@stop