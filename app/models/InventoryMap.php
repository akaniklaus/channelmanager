<?php

class InventoryMap extends \Eloquent
{


    protected $primaryKey = ['property_id', 'channel_id', 'room_id'];

    public static $rules = [
        'room_id' => 'required'
    ];

    protected $fillable = ['name', 'room_id', 'inventory_code', 'property_id', 'channel_id'];


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * @param $query
     * @param $channelId
     * @param $propertyId
     * @param $roomId
     * @return mixed
     */
    public function scopeGetByKeys($query, $channelId, $propertyId, $roomId)
    {
        return $query->where([
            'room_id' => $roomId,
            'channel_id' => $channelId,
            'property_id' => $propertyId
        ]);
    }
}