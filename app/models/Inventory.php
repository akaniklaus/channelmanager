<?php

class Inventory extends \Eloquent
{
    protected $primaryKey = ['property_id', 'channel_id', 'code'];
    public $incrementing = false;

    protected $fillable = ['name', 'code', 'channel_id', 'property_id'];

    public function plans()
    {
        return $this->hasMany('InventoryPlan', 'inventory_code', 'code')->where('channel_id', $this->channel_id);
    }

    /**
     * @param $query
     * @param $code
     * @param $channelId
     * @param $propertyId
     * @return mixed
     */
    public function scopeGetByKeys($query, $channelId, $propertyId, $code)
    {
        return $query->where([
            'code' => $code,
            'channel_id' => $channelId,
            'property_id' => $propertyId,
        ]);
    }
}