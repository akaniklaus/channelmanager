@extends('layouts.master')

@section('menu')
<li><a href="{{URL::action('PropertiesChannelsController@getCreate')}}">Add channel settings</a></li>
@stop