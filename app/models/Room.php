<?php

/**
 * Room
 *
 * @property integer $id
 * @property string $name
 * @property float $rack_rate
 * @property integer $property_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \InventoryMap $mapping
 * @property-read \InventoryMap $mappingList
 * @method static \Illuminate\Database\Query\Builder|\Room whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereRackRate($value)
 * @method static \Illuminate\Database\Query\Builder|\Room wherePropertyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereUpdatedAt($value)
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
        return $this->hasOne('InventoryMap', 'room_id')->where('channel_id', $channelId);
    }

    public function mappingList()
    {
        return $this->hasOne('InventoryMap', 'room_id')->get();
    }

}