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
 * @property string $type
 * @property integer $parent_id
 * @property string $formula_type
 * @property float $formula_value
 * @property-read \Room $parent
 * @method static \Illuminate\Database\Query\Builder|\Room whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereFormulaType($value)
 * @method static \Illuminate\Database\Query\Builder|\Room whereFormulaValue($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Room[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\Room[] $plans
 * @method static \Room forBulkRate($propertyId)
 * @method static \Room forBulkAvailability($propertyId)
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

    public function children()
    {
        return $this->hasMany('Room', 'parent_id', 'id');
    }

    public function plans()
    {
        return $this->hasMany('Room', 'parent_id', 'id')->where('type', 'plan');
    }

    public function scopeForBulkRate($query, $propertyId)
    {
        return $query->where('property_id', $propertyId)
            ->where(function ($query) {
                $query->where('parent_id', 0)->orWhereNull('parent_id');
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))->from('inventory_maps as im')
                    ->whereRaw('im.room_id = rooms.id');
            });
    }

    public function scopeForBulkAvailability($query, $propertyId)
    {
        return $query
            ->where('property_id', $propertyId)
            ->where('type', 'room')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))->from('inventory_maps as im')
                    ->whereRaw('im.room_id = rooms.id');
            });
    }
}