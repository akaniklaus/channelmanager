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
}