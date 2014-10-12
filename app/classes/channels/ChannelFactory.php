<?php namespace Channels;
/**
 * Class ChannelFactory
 * @package Channels
 */
class ChannelFactory
{

    /**
     * @param $chanelId
     * @return Expedia
     */
    public static function create($chanelId)
    {
        switch ($chanelId) {
            case 3:
                return new Expedia();
        }
    }

}