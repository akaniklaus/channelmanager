@extends('layouts.rooms')
@section('content')
<h2>Common Rooms</h2>

<table class="table">
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


@foreach ($channels as $channelSettings)
    @if($channel = $channelSettings->channel())
        <h2>{{$channel->name}} Rooms</h2>
        <table  class="table">
            <tr>
                <th>id</th>
                <th>name</th>
                <th>Mapped room</th>
                <th>actions</th>
            </tr>
            @foreach ($rooms as $room)
                <tr>
                    <td>{{$room->id}}</td>
                    <td>{{$room->name}}</td>
                    <td>@if($mapping = $room->mapping($channel->id)->get())
                            @foreach($mapping as $map)
                                {{$map->name}}: {{$map->plan_name}} ({{$map->plan_code}})  <br/>
                            @endforeach
                    @endif
                    </td>
                    <td>
                    {{link_to_action('RoomsController@getMap','Map',[$room->id,$channel->id])}}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
@endforeach
@stop
