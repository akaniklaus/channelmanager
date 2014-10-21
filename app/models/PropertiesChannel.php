<?php

/**
 * PropertiesChannel
 *
 * @property-read \Channel $channel
 * @property string $login
 * @property string $password
 * @property string $hotel_code
 * @property integer $property_id
 * @property integer $channel_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel whereLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel whereHotelCode($value)
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel wherePropertyId($value)
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\PropertiesChannel whereUpdatedAt($value)
 */
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

    /**
     * @param $channelId
     * @param $propertyId
     * @return \Illuminate\Database\Eloquent\Model|null|static|PropertiesChannel
     */
    public static function getSettings($channelId, $propertyId)
    {
        return PropertiesChannel::where(
            [
                'channel_id' => $channelId,
                'property_id' => $propertyId
            ]
        )->first();
    }

}