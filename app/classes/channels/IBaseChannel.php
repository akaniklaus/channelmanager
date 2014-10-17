<?php namespace Channels;
/**
 * Class IBaseExpedia
 * @package Channels
 */
interface IBaseChannel
{
    public function getInventoryList();

    /**
     * @param string $currency
     */
    public function setCurrency($currency);
}