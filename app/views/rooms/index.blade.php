@extends('layouts.rooms')
@section('content')
<h2>Common Rooms</h2>

<table class="table">
    <tr>
        <th>id</th>
        <th>name</th>
        <th>rack rate</th>
        <th>property</th>
        <th>type</th>
        <th>formula</th>
        <th>actions</th>
    </tr>
@foreach ($rooms as $room)
    <tr>
        <td>{{$room->id}}</td>
        <td>{{$room->name}}</td>
        <td>{{$room->rack_rate}}</td>
        <td>{{$room->property_id}}</td>
        <td>{{$room->type}}</td>
        <td>@if($parent = $room->parent()){{$room->parent()->name}} {{$room->formula_type}} {{$room->formula_value}}@endif</td>
        <td>
        {{link_to_action('RoomsController@getEdit','Edit',$room->id)}}
        | {{link_to_action('RoomsController@getDestroy','Delete',$room->id)}}

        </td>
    </tr>
@endforeach
</table>

@include('rooms.channels')
@stop