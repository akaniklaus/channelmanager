<?php

/**
 * Channel
 *
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