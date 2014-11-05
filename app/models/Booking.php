<?php

/**
 * Booking
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $channel_id
 * @property integer $room_id
 * @property string $reservation_id
 * @property string $rr_id
 * @property string $inventory
 * @property string $plan
 * @property string $date_arrival
 * @property string $date_departure
 * @property boolean $count_adult
 * @property boolean $count_child
 * @property boolean $count_child_age
 * @property string $guest_firstname
 * @property string $guest_lastname
 * @property string $comments
 * @property float $total
 * @property string $currency
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Booking whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking wherePropertyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereReservationId($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereRrId($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereInventory($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking wherePlan($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereDateArrival($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereDateDeparture($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereCountAdult($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereCountChild($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereCountChildAge($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereGuestFirstname($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereGuestLastname($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereComments($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereCurrency($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Booking whereUpdatedAt($value)
 * @method static \Booking getByKeys($channelId, $propertyId)
 * @property string $prices
 * @method static \Illuminate\Database\Query\Builder|\Booking wherePrices($value)
 */
class Booking extends \Eloquent
{
    protected $fillable = [
        'property_id', 'channel_id', 'room_id', 'reservation_id', 'rr_id', 'guest_firstname', 'guest_lastname',
        'comments', 'inventory', 'plan', 'date_arrival', 'date_departure',
        'count_adult', 'count_child', 'count_child_age', 'total', 'currency', 'prices'
    ];

    /**
     * @param $query
     * @param $channelId
     * @param $propertyId
     * @return Reservation
     */
    public function scopeGetByKeys($query, $channelId, $propertyId)
    {
        return $query->where([
            'channel_id' => $channelId,
            'property_id' => $propertyId,
        ]);
    }
}