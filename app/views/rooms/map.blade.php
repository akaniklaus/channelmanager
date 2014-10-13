@extends('layouts.rooms')
@section('content')
<script>
var PLANS = {{$inventoryPlans}}
$(function() {
  $('#inventory_id').on('change',function(){
    $('#plans').text('');
    for(var inventory_id in PLANS){
      if($('#inventory_id').val() != inventory_id){
        continue;
      }
      for(var i in PLANS[inventory_id]){
      $('#plans').append('<li>'+PLANS[inventory_id][i].id+':'+PLANS[inventory_id][i].name+'</li>');
      }
    }
  })
})

</script>
{{Form::model($room,['action'=>['RoomsController@postMap']])}}
{{Form::hidden('room_id',$room->id)}}
{{Form::hidden('channel_id',$channelId)}}
{{Form::label('name','Room name:')}} {{{$room->name}}}
<br/>
{{Form::label('inventory_id','Channel inventory name:')}}
{{Form::select('inventory_id',$inventoryList,$mapping->inventory_id)}}
<br/>
<ul id="plans"></ul>
{{Form::submit('Map room')}}
{{Form::close()}}

@stop