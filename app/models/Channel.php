<?php

/**
 * Channel
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Inventory[] $inventory
 * @method static \Illuminate\Database\Query\Builder|\Channel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Channel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Channel whereUpdatedAt($value)
 */
class Channel extends \Eloquent
{
    protected $fillable = ['id', 'name'];
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function inventory()
    {
        return $this->hasMany('Inventory');
    }
}