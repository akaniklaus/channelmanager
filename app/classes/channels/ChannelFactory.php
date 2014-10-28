<?php namespace Channels;
/**
 * Class ChannelFactory
 * @package Channels
 */
class ChannelFactory
{

    /**
     * @param PropertiesChannel $channelSettings
     * @return BookingDotCom
     */
    public static function create($channelSettings)
    {
        switch ($channelSettings->channel_id) {
            case 1:
                return new BookingDotCom($channelSettings);
            case 3:
                return new Expedia($channelSettings);
        }
        return false;
    }

}