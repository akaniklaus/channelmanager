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
        'name' => 'required',
        'type' => 'required|in:"room","plan"',
        'formula_type' => 'required_with:parent_id|in:"x","-","+"',
        'formula_value' => 'required_with:formula_type|numeric|min:0'
    ];

    // Don't forget to fill this array
    protected $fillable = ['name', 'rack_rate', 'property_id', 'type', 'parent_id', 'formula_type', 'formula_value'];

    public static $formulaTypes = ['x' => 'x', '+' => '+', '-' => '-'];

    public function mapping($channelId)
    {
        return $this->hasOne('InventoryMap', 'room_id')->where('channel_id', $channelId);
    }

    public function mappingList()
    {
        return $this->hasOne('InventoryMap', 'room_id')->get();
    }

    public function parent()
    {
        return $this->belongsTo('Room', 'parent_id', 'id')->first();
    }

}