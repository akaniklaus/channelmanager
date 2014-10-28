<?php namespace Channels;

use Illuminate\Support\Facades\Log;

/**
 * Class BaseChannel
 * @package Channels
 */
abstract class BaseChannel
{
    protected $debug = true;

    protected $testMode = true;

    protected $currency = 'EUR';

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    protected function getTestMode()
    {
        return $this->testMode ? 'test' : 'live';
    }

    public function setTestMode($test = true)
    {
        $this->testMode = (bool)$test;
    }

    /**
     * @param $url
     * @param string $data
     * @param array $headers
     * @return mixed
     */
    protected function processCurl($url, $data = "", $headers = [])
    {
        if ($this->debug) {
            \Log::debug($data);
        }

        set_time_limit(600);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!is_array($headers)) {
            $headers = [];
        }
        array_merge($headers, [
            'Accept: application/xml',
            'Connection: Keep-Alive',
            'Accept-Language: en-us',
            'Accept-Encoding: gzip, deflate',
            'Content-Type: application/xml'
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
            \Log::error(curl_error($ch));
        } else {
            if ($this->debug) {
                \Log::debug($result);
            }
        }

        curl_close($ch);
        return $result;
    }

}