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