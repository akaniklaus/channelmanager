<?php

/**
 * Room
 *
 */
class Room extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        'name' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['name', 'rack_rate', 'property_id'];

    public function mapping($channelId)
    {
        return $this->hasOne('InventoryMap', 'room_id')->where('channel_id', $channelId)->first();
    }

    public function mappingList()
    {
        return $this->hasOne('InventoryMap', 'room_id')->get();
    }

}