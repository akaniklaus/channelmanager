<?php

class Property extends \Eloquent
{

    const PROPERTY_ID = 'property_id';

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['name'];

    public static function getLoggedId()
    {
        return Session::get(Property::PROPERTY_ID, 1);
    }

    public function channels()
    {
        return $this->hasMany('Channel');
    }
}