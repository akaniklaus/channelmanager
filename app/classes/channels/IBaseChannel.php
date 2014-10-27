<?php namespace Channels;
/**
 * Class IBaseExpedia
 * @package Channels
 */
interface IBaseChannel
{
    /**
     * @param PropertiesChannel $channelSettings
     */
    public function __construct($channelSettings);

    public function getInventoryList();

    /**
     * @param string $currency
     */
    public function setCurrency($currency);

    /**
     * Set rate on channel
     *
     * @param string $roomId
     * @param string $ratePlanId
     * @param string $fromDate
     * @param string $toDate
     * @param array $days
     * @param float $rate
     * @return mixed
     */
    public function setRate($roomId, $ratePlanId, $fromDate, $toDate, $days, $rate);

    /**
     * Set availability on channel
     *
     * @param string $roomId
     * @param string $fromDate
     * @param string $toDate
     * @param array $days
     * @param integer $availability
     * @return mixed
     */
    public function setAvailability($roomId, $fromDate, $toDate, $days, $availability);
}