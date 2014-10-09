<?php namespace Channels;
/**
 * Class Expedia
 * @package Channels
 * @doc http://www.expediaquickconnect.com/system/assets/attachments/434/EQC_Public_API.pdf
 */
class Expedia
{
    protected $testMode = true;
    protected $auth = [
        'test' => [
            'username' => 'testuser',
            "password" => 'ECLPASS'
        ]
    ];

    protected $hotelCode;

    protected $urls = [
        'get_rooms' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/parr',
            'live' => 'https://ws.expediaquickconnect.com/connect/parr'
        ]
    ];

    protected $ns = [
        'PAR' => 'http://www.expediaconnect.com/EQC/PAR/2013/07'
    ];

    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;
    }

    public function setTestMode($test = true)
    {
        $this->testMode = (bool)$test;
    }

    protected function getUrl($type)
    {
        return $this->urls[$type][$this->testMode];
    }

    protected function getAuth($key)
    {
        return $this->auth[$this->testMode][$key];
    }

    protected function prepareXml($callName, $ns, $xml = "")
    {
        return
            '<?xml version="1.0" encoding="UTF-8" ?>'
            . '<' . $callName . ' xmlns="' . $ns . '" >'
            . '<Authentication username="' . $this->getAuth('username') . '" password="' . $this->getAuth('password') . '"/>'
            . '<Hotel id="' . $this->hotelCode . '"/>' . $xml
            . '</' . $callName . '>';
    }
}