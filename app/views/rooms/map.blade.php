@extends('layouts.rooms')
@section('content')
<script>
var PLANS = {{$inventoryPlans}}
$(function() {
  $('#code').on('change',function(){
    $('#plans').text('');
    for(var code in PLANS){
      if($('#code').val() != code){
        continue;
      }
      for(var i in PLANS[code]){
      $('#plans').append('<li>'+PLANS[code][i].code+':'+PLANS[code][i].name+'</li>');
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
{{Form::label('code','Channel inventory name:')}}
{{Form::select('code',[''=>'Unmapped']+$inventoryList,($mapping)?$mapping->inventory_code:'')}}
<br/>
<ul id="plans"></ul>
{{Form::submit('Map room')}}
{{Form::close()}}

@stop