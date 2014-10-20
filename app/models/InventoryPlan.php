<?php

/**
 * InventoryPlan
 *
 * @property string $name
 * @property string $code
 * @property integer $channel_id
 * @property string $inventory_code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $property_id
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan whereInventoryCode($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InventoryPlan wherePropertyId($value)
 */
class InventoryPlan extends \Eloquent
{
    protected $primaryKey = ['channel_id', 'inventory_code', 'code', 'property_id'];
    public $incrementing = false;

    protected $fillable = ['name', 'code', 'channel_id', 'inventory_code', 'property_id'];
}