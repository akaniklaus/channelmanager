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
          var checked = '';
          if(PLANS[code][i].selected){
            checked = 'checked="checked"';
          }
          $('#plans').append('<label><input type="checkbox" name="plans[]" value="'+PLANS[code][i].code+'" '+ checked +' />'+PLANS[code][i].code+':'+PLANS[code][i].name+'</label><br/>');
      }
    }
  })
  $('#code').change();
})

</script>
{{Form::model($room,['action'=>['RoomsController@postMap']])}}
{{$errors->first('room_id')}}
{{Form::hidden('room_id',$room->id)}}
{{Form::hidden('channel_id',$channelId)}}
{{Form::label('name','Room name:')}} {{{$room->name}}}
<br/>
{{Form::label('code','Channel Inventory Code:')}}<br/>
{{Form::select('code',[''=>'Unmapped']+$inventoryList,($mapping)?$mapping->inventory_code:'')}}
<br/><br/>
{{Form::label('code','Inventory Plan:')}}<br/>
{{$errors->first('plans')}}
<div id="plans"></div>
{{Form::submit('Map room')}}
{{Form::close()}}

@stop