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


    public function __construct($channelSettings)
    {
        $this->setHotelCode($channelSettings->hotel_code);
    }

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
        if (!$result['success']) {
            return [];
        }
        $inventories = [];
        foreach ($result['data']->ProductList->RoomType as $one) {
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
        $daysMap = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        $daysStr = '';
        foreach ($daysMap as $key => $name) {
            $daysStr .= $name . '="' . (in_array($key, $days) ? 'true' : 'false') . '" ';
        }
        return $daysStr;
    }

    public function setRate($roomId, $ratePlanId, $fromDate, $toDate, $days, $rate)
    {
        $xml = '<AvailRateUpdate>' .
            '<DateRange from="' . $fromDate . '" to="' . $toDate . '" ' . $this->getWeekDaysStr($days) . '/>' .
            '<RoomType id="' . $roomId . '">' .
            '<RatePlan id="' . $ratePlanId . '">' .
            '<Rate currency="' . $this->getCurrency() . '">' .
            '<PerDay rate="' . $rate . '"/>' .
            '</Rate>' .
            '</RatePlan>' .
            '</RoomType>' .
            '</AvailRateUpdate>';
        $xml = $this->prepareXml('AvailRateUpdate', $this->ns['AR'], $xml);
        $result = $this->processCurl($this->getUrl(__FUNCTION__), $xml);
        if ($result['success']) {
            return true;
        }
        return $result['errors'];
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
     * @return bool|array|mixed|\SimpleXMLElement
     */
    protected function processCurl($url, $data = "", $headers = [])
    {
        $success = false;
        $errors = [];
        $result = parent::processCurl($url, $data, $headers);
        if ($result) {
            $resultObj = simplexml_load_string($result);
            if ($resultObj->Error) {
                foreach ($resultObj->Error as $error) {
                    $errors[] = (string)$error;
                }
            } else {
                $success = true;
            }
        }
        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $result
        ];
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