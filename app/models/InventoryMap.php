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
 * @property string $plan_code
 * @property string $plan_name
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap wherePlanCode($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryMap wherePlanName($value)
 * @method static \InventoryMap getMappedRoom($channelId, $propertyId, $inventoryCode, $planCode = null)
 */
class InventoryMap extends \Eloquent
{


    protected $primaryKey = ['property_id', 'channel_id', 'room_id'];

    public static $rules = [
        'room_id' => 'required',
        'plans' => 'required_with:code'
    ];

    protected $fillable = ['name', 'room_id', 'inventory_code',
        'property_id', 'channel_id', 'plan_code', 'plan_name'];


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
     * @param $planCode
     * @return mixed
     */
    public function scopeGetByKeys($query, $channelId = null, $propertyId, $roomId, $planCode = null)
    {
        $params = [
            'room_id' => $roomId,
            'property_id' => $propertyId
        ];
        if ($channelId) {
            $params['channel_id'] = $channelId;
        }
        if ($planCode) {
            $params['plan_code'] = $planCode;
        }
        return $query->where($params);
    }

    public function inventory()
    {
        return $this->hasOne('Inventory', 'code', 'inventory_code')->where('channel_id', $this->channel_id)->first();
    }

    public function scopeGetMappedRoom($query, $channelId, $propertyId, $inventoryCode, $planCode = null)
    {
        $params = [
            'property_id' => $propertyId,
            'channel_id' => $channelId,
            'inventory_code' => $inventoryCode
        ];
        if ($planCode) {
            $params['plan_code'] = $planCode;
        }
        return $query->where($params);
    }
}