@extends('layouts.master')
@section('scripts')
{{ HTML::script('js/bulk/bulk.js') }}
<script>
    var BULK_UPDATE_URL = "{{URL::action('BulkController@postUpdateAvailability')}}"
</script>
@stop
@section('content')

{{Form::open(['id'=>'bulk_form','class'=>'form-horizontal'])}}

<div class="row">
  <div class="col-xs-12 col-md-4">
        <label>From date
            <div class="input-group date">
                {{Form::text('from_date',null,['class'=>'form-control'])}}
                <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
            </div>
        </label>
  </div>
  <div class="col-xs-12 col-md-4">
        <label>To date
            <div class="input-group date">
               {{Form::text('to_date',null,['class'=>'form-control'])}}
               <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
             </div>
        </label>
  </div>
</div>
<div class="row">
  <div class="col-xs-6 col-md-4 bulk-weekdays">
          <label>On one or more of these days:</label>
          <p>
            <button type="button" class="btn btn-link btn-xs"  onclick="bulkSelectAll('bulk-weekdays')">Select All</button>
            <button type="button" class="btn btn-link btn-xs"  onclick="bulkUnSelectAll('bulk-weekdays')">Unselect All</button>
          </p>
          <div class="checkbox">
            <label>{{Form::checkbox('week_day[]',0,true)}}Monday</label>
          </div>
          <div class="checkbox">
              <label>{{Form::checkbox('week_day[]',1,true)}}Tuesday</label>
          </div>
          <div class="checkbox">
            <label>{{Form::checkbox('week_day[]',2,true)}}Wednesday</label>
          </div>
          <div class="checkbox">
              <label>{{Form::checkbox('week_day[]',3,true)}}Thursday</label>
          </div>
          <div class="checkbox">
              <label>{{Form::checkbox('week_day[]',4,true)}}Friday</label>
          </div>
          <div class="checkbox">
              <label>{{Form::checkbox('week_day[]',5,true)}}Saturday</label>
          </div>
          <div class="checkbox">
              <label>{{Form::checkbox('week_day[]',6,true)}}Sunday</label>
          </div>
  </div>

  <div class="col-xs-6 col-md-4 bulk-rooms">
        <label>One or more of these Rooms:</label>
        <p>
            <button type="button" class="btn btn-link btn-xs"  onclick="bulkSelectAll('bulk-rooms')">Select All</button>
            <button type="button" class="btn btn-link btn-xs"  onclick="bulkUnSelectAll('bulk-rooms')">Unselect All</button>
        </p>
        @foreach ($rooms as $room)
            <div class="checkbox">
              <label>{{Form::checkbox('rooms[]',$room->id)}}{{{$room->name}}}</label>
              @include('bulk.availability_children',['currentRoom'=>$room])
            </div>
        @endforeach
  </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="col-xs-12 col-md-6 ">
            {{Form::label('availability','Number of rooms to make available:',['class'=>'control-label'])}}
        </div>
        <div class="col-xs-12 col-md-6">
         {{Form::text('availability',null,['class'=>'form-control'])}}
        </div>
    </div>
</div>

<br/>
<button type="button" id="loading-example-btn" data-loading-text="Updating..." class="btn btn-primary btn-default" >Update</button>

{{Form::close()}}

@stop