<?php

/**
 * Inventory
 *
 * @property string $name
 * @property string $code
 * @property integer $channel_id
 * @property integer $property_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\InventoryPlan[] $plans
 * @method static \Illuminate\Database\Query\Builder|\Inventory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Inventory whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Inventory whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\Inventory wherePropertyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Inventory whereUpdatedAt($value)
 * @method static \Inventory getByKeys($channelId, $propertyId, $code)
 */
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