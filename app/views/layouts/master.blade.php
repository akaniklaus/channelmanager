<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CM</title>
</head>
<body>
@section('sidebar')
    <div>
        Menu:
        <ul>
            <li>{{link_to_action('HomeController@getIndex','Home')}}</li>
            <li>{{link_to_action('PropertiesController@getIndex','Properties')}}</li>
            <li>{{link_to_action('RoomsController@getIndex','Rooms list')}}</li>
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
