<?php

class PropertiesChannel extends \Eloquent
{

//    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['login', 'password', 'hotel_code', 'channel_id', 'property_id'];

    public function channel()
    {
        return $this->belongsTo('Channel', 'channel_id')->first();
    }

}