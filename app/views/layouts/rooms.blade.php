@extends('layouts.master')

@section('menu')
<li><a href="{{URL::action('RoomsController@getCreate')}}">Add room</a></li>
@stop