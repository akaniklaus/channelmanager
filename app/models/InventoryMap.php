<?php

/**
 * InventoryMap
 *
 * @property string $name
 * @property integer $room_id
 * @property integer $property_id
 * @property integer $channel_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $inventory_code
 * @property-read \Inventory $inventory
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap wherePropertyId($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap whereInventoryCode($value)
 * @method static \InventoryMap getByKeys($channelId, $propertyId, $roomId)
 */
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
    public function scopeGetByKeys($query, $channelId = null, $propertyId, $roomId)
    {
        $params = [
            'room_id' => $roomId,
            'property_id' => $propertyId
        ];
        if ($channelId) {
            $params['channel_id'] = $channelId;
        }
        return $query->where($params);
    }

    public function inventory()
    {
        return $this->hasOne('Inventory', 'code', 'inventory_code')->where('channel_id', $this->channel_id)->first();
    }
}