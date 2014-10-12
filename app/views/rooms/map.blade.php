@extends('layouts.rooms')
@section('content')
<script>
//TODO FINISH ONCHAGE
var PLANS = {{json_encode($inventoryPlans)}}
$.ready(function() {
  $('#inventory_id').change(function(){
      $('#plans').clear();
      for(var inventory_id in PLANS){
          $('#plans').append('<li>'+PLANS[inventory_id].id+':'+PLANS[inventory_id].name+'</li>');
      }
  })
})

</script>
{{Form::model($room,['action'=>['RoomsController@postMap',$room->id]])}}
{{Form::label('name','Room name:')}} {{{$room->name}}}
<br/>
{{Form::label('inventory_id','Channel inventory name:')}}
{{Form::select('inventory_id',$inventoryList)}}
<br/>
<ul id="plans"></ul>
{{Form::submit('Map room')}}
{{Form::close()}}

@stop