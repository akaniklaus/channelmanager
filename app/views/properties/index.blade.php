@extends('layouts.properties')
@section('content')
<table class="table">
<tr>
<th>id</th>
<th>name</th>
{{--<th>actions</th>--}}
</tr>
@foreach ($properties as $property)
    <tr>
        <td>{{$property->id}}</td>
        <td>{{$property->name}}</td>
        <td>
        {{--{{link_to_action('PropertiesController@getEdit','Edit',$property->id)}}--}}
        {{--| {{link_to_action('PropertiesController@getDestroy','Delete',$property->id)}}--}}

        </td>

    </tr>
@endforeach
</table>
@stop
