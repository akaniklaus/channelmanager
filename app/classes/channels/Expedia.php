<?php namespace Channels;
/**
 * Class Expedia
 * @package Channels
 * @doc http://www.expediaquickconnect.com/system/assets/attachments/434/EQC_Public_API.pdf
 */
class Expedia extends BaseChannel implements IBaseChannel
{
    protected $auth = [
        'test' => [
            'username' => 'testuser',
            "password" => 'ECLPASS'
        ]
    ];

    protected $hotelCode;

    protected $urls = [
        'getInventoryList' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/parr',
            'live' => 'https://ws.expediaquickconnect.com/connect/parr'
        ],
        'setRate' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/ar',
            'live' => 'https://ws.expediaquickconnect.com/connect/ar'
        ]
    ];

    protected $ns = [
        'PAR' => 'http://www.expediaconnect.com/EQC/PAR/2013/07',//getInventoryList
        'AR' => 'http://www.expediaconnect.com/EQC/AR/2011/06'
    ];

    /**
     * @return array
     */
    public function getInventoryList()
    {
        $xml =
            '<ParamSet>' .
            '<ProductRetrieval ' .
            'returnRateLink="false" ' .
            'returnRoomAttributes="false" ' .
            'returnRatePlanAttributes="true" ' .
            'returnCompensation="false" ' .
            'returnCancelPolicy="false" />' .
            '</ParamSet>';
        $xml = $this->prepareXml('ProductAvailRateRetrieval', $this->ns['PAR'], $xml);
        $result = $this->processCurl($this->getUrl(__FUNCTION__), $xml);
        $inventories = [];
        foreach ($result->ProductList->RoomType as $one) {
            $inventory = [
                'code' => (string)$one['id'],
                'name' => (string)$one['name']
            ];
            foreach ($one->RatePlan as $plan) {
                $inventory['plans'][] = [
                    'code' => (string)$plan['id'],
                    'name' => (string)$plan['name'],
//                    'extra' => $plan
                    //TODO need find out about extra
                ];
            }

            $inventories[] = $inventory;

        }

        return $inventories;

        //TODO add rate plans, manage derived and price type

    }

    protected function getWeekDaysStr($days)
    {
        $daysMap = ['mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6, 'sun' => 7];
        $daysStr = '';
        foreach ($daysMap as $name => $value) {
            $daysStr .= $name . '="' . isset($days[$value]) && $days[$value] ? 'true' : 'false';
        }
    }

    protected function setRate($roomId, $ratePlanId, $fromDate, $toDate, $days, $rate)
    {
        $xml = '<AvailRateUpdate>' .
            '<DateRange from="' . $fromDate . '" to="' . $toDate . '" ' . $this->getWeekDaysStr($days) . ' />' .
            '<RoomType id="' . $roomId . '">' .
            '<RatePlan id="' . $ratePlanId . '">' .
            '<Rate currency="' . $this->getCurrency() . '">' .
            '<PerDay rate="' . $rate . '"/>' .
            '</Rate>' .
            '</RatePlan>' .
            '</RoomType>' .
            '</AvailRateUpdate>';
        $xml = $this->prepareXml('<AvailRateUpdate', $this->ns['AR'], $xml);
        $result = $this->processCurl($this->getUrl(__FUNCTION__), $xml);
    }


    protected function prepareXml($callName, $ns, $xml = "")
    {
        return '<?xml version = "1.0" encoding = "UTF-8" ?>'
        . '<' . $callName . 'RQ xmlns="' . $ns . '" >'
        . '<Authentication username="' . $this->getAuth('username') . '" password="' . $this->getAuth('password') . '"/>'
        . '<Hotel id="' . $this->hotelCode . '"/>' . $xml
        . '</' . $callName . 'RQ>';
    }


    /**
     * @param $url
     * @param string $data
     * @param array $headers
     * @return bool|mixed|\SimpleXMLElement
     */
    protected function processCurl($url, $data = "", $headers = [])
    {
        $result = parent::processCurl($url, $data, $headers);
        if ($result) {
            return simplexml_load_string($result);
        }
        return false;
    }

    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;
    }

    protected function getUrl($type)
    {
        return $this->urls[$type][$this->getTestMode()];
    }

    protected function getAuth($key)
    {
        return $this->auth[$this->getTestMode()][$key];
    }
}