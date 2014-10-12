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
        'requestRooms' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/parr',
            'live' => 'https://ws.expediaquickconnect.com/connect/parr'
        ]
    ];

    protected $ns = [
        'PAR' => 'http://www.expediaconnect.com/EQC/PAR/2013/07'
    ];


    protected function requestRooms()
    {
        $xml = $this->prepareXml('ProductAvailRateRetrieval', $this->ns['PAR']);
        $result = $this->processCurl($this->getUrl(__METHOD__), $xml);
        var_dump($result);
        die;

    }

    protected function prepareXml($callName, $ns, $xml = "")
    {
        return
            '<?xml version="1.0" encoding="UTF-8" ?>'
            . '<' . $callName . 'RQ xmlns="' . $ns . '" >'
            . '<Authentication username="' . $this->getAuth('username') . '" password="' . $this->getAuth('password') . '"/>'
            . '<Hotel id="' . $this->hotelCode . '"/>' . $xml
            . '</' . $callName . 'RQ>';
    }

    function processCurl($url, $data = "", $headers = null)
    {
        set_time_limit(600);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5');
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        if (!empty($data)) {
            if (is_array($data)) {
                $strData = "";
                foreach ($data as $k => $v) {
                    $strData .= $k . '=' . rawurlencode($v) . '&';
                }
                $data = substr($strData, 0, -1);
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $result = curl_exec($ch);

        if (curl_exec($ch) === false) {
            Log::error(curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }


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
}