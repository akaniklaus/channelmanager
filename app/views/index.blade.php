@extends('layouts.master')
@section('content')
@if(isset($execResult))
updated: {{$execResult['updated']}} <br/>
created: {{$execResult['created']}} <br/>
bookings: {{$execResult['bookings']}} <br/>
cancelled: {{$execResult['cancelled']}} <br/>
not mapped: {{$execResult['not_mapped']}} <br/>
@endif
@stop