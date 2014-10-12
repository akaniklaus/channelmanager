<?php

/**
 * Room
 *
 */
class Room extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        'name' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['name', 'rack_rate', 'property_id'];

}