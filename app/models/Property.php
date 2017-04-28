<?php

/**
 * Property
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Channel[] $channels
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $currency
 * @method static \Illuminate\Database\Query\Builder|\Property whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Property whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Property whereCurrency($value)
 */
class Property extends \Eloquent
{

    const PROPERTY_ID = 'property_id';

    // Add your validation rules here
    public static $rules = [
        'name' => 'required', 
        'currency' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['name', 'currency'];

    public static function getLoggedId()
    {
        return Session::get(Property::PROPERTY_ID, 1);
    }

    public function channels()
    {
        return $this->hasMany('Channel');
    }
}