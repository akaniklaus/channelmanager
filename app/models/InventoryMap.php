<?php

class InventoryMap extends \Eloquent
{
    public static $rules = [
        'room_id' => 'required'
    ];

    protected $fillable = ['name', 'room_id', 'inventory_id', 'property_id', 'channel_id'];


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @param $roomId
     * @param $channelId
     * @param $propertyId
     * @param $first
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function getMapping($roomId, $channelId, $propertyId, $first = true)
    {
        $obj = InventoryMap::where(
            [
                'room_id' => $roomId,
                'channel_id' => $channelId,
                'property_id' => $propertyId
            ]
        );

        return $first ? $obj->first() : $obj;
    }
}