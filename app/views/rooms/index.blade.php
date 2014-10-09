@extends('layouts.rooms')

@section('content')
<table>
<tr>
<th>id</th>
<th>name</th>
<th>rack rate</th>
<th>property</th>
<th>actions</th>
</tr>
@foreach ($rooms as $room)
    <tr>
        <td>{{$room->id}}</td>
        <td>{{$room->name}}</td>
        <td>{{$room->rack_rate}}</td>
        <td>{{$room->property_id}}</td>
        <td>
        {{link_to_action('RoomsController@getEdit','Edit',$room->id)}}
        | {{link_to_action('RoomsController@getDestroy','Delete',$room->id)}}

        </td>

    </tr>
@endforeach
</table>
@stop
