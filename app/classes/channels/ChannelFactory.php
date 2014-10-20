<?php namespace Channels;
/**
 * Class ChannelFactory
 * @package Channels
 */
class ChannelFactory
{

    /**
     * @param PropertiesChannel $channelSettings
     * @return Expedia
     */
    public static function create($channelSettings)
    {
        switch ($channelSettings->channel_id) {
            case 3:
                return new Expedia($channelSettings);
        }
    }

}