@extends('layouts.properties_channels')

@section('content')
<table>
<tr>
<th>login</th>
<th>password</th>
<th>hotel_code</th>
<th>property_id</th>
<th>channel_id</th>
<th>actions</th>
</tr>
@foreach ($propertieschannels as $pc)
    <tr>
        <td>{{$pc->login}}</td>
        <td>{{$pc->password}}</td>
        <td>{{$pc->hotel_code}}</td>
        <td>{{$pc->property_id}}</td>
        <td>{{$pc->channel()->name}}</td>
        <td>
        {{link_to_action('PropertiesChannelsController@getEdit','Edit',$pc->channel_id)}}
        </td>

    </tr>
@endforeach
</table>
@stop