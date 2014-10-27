<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CM</title>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/datepicker3.css') }}

    {{ HTML::script('js/jquery-1.11.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/bootstrap-datepicker.js') }}
    @yield('scripts')
</head>
<body>
@section('sidebar')
{{Form::open(array('action'=>'PropertiesController@getSwitch','method'=>'get'))}}
{{Form::label('property_id','Property')}}
{{Form::select('property_id',Property::lists('name', 'id'),Session::get(Property::PROPERTY_ID))}}
{{$errors->first('property_id')}}
{{Form::submit('select')}}
{{Form::close()}}
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
         <ul class="nav navbar-nav">
              @section('menu')
              @show
         </ul>
        <ul class="nav navbar-nav navbar-right">
          <li @if(!Request::segment(1)) class="active" @endif>{{link_to_action('HomeController@getIndex','Home')}}</li>
          <li @if(Request::segment(1)==="properties") class="active" @endif>{{link_to_action('PropertiesController@getIndex','Properties')}}</li>
          <li @if(Request::segment(1)==="channels") class="active" @endif>{{link_to_action('PropertiesChannelsController@getIndex','Channels settings')}}</li>
          <li @if(Request::segment(1)==="rooms") class="active" @endif>{{link_to_action('RoomsController@getIndex','Rooms list')}}</li>
          <li @if(Request::segment(1)==="bulk"&& Request::segment(2)==="index" ) class="active" @endif>{{link_to_action('BulkController@getIndex','Bulk Rates')}}</li>
          <li @if(Request::segment(1)==="bulk" && Request::segment(2)==="availability" ) class="active" @endif>{{link_to_action('BulkController@getAvailability','Bulk Availability')}}</li>
        </ul>
    </div>
  </div>
</nav>
@show
<div class="container">
    @yield('content')
</div>
</body>
</html>
