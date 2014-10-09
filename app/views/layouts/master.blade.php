<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CM</title>
</head>
<body>
@section('sidebar')
{{Form::open(array('action'=>'PropertiesController@getSwitch','method'=>'get'))}}
{{Form::label('property_id','Property')}}
{{Form::select('property_id',Property::lists('name', 'id'),Session::get(Property::PROPERTY_ID))}}
{{$errors->first('property_id')}}
{{Form::submit('select')}}
{{Form::close()}}
    <div>
        Menu:
        <ul>
            <li>{{link_to_action('HomeController@getIndex','Home')}}</li>
            <li>{{link_to_action('PropertiesController@getIndex','Properties')}}</li>
            <li>{{link_to_action('RoomsController@getIndex','Rooms list')}}</li>
            <li>{{link_to_action('PropertiesChannelsController@getIndex','Channels settings')}}</li>
            @section('menu')
            @show
        </ul>
    </div>
@show
<div>
    @yield('content')
</div>
</body>
</html>
