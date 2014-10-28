<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10/28/14
 * Time: 1:03 PM
 */

namespace Channels;

/**
 * Class BookingDotCom
 * https://supply-xml.booking.com/documentation/hotels/documentation/index.html
 * https://supply-xml.booking.com/affiliates/documentation/
 * Username: XMLSPECS
 * Password: SPECSXML
 *
 * @package Channels
 */
class BookingDotCom extends BaseChannel implements IBaseChannel
{

    protected $hotelCode;

    protected $urls = [
        'getInventoryList' => [
            'test' => 'http://cm.kamer.ngrok.com/simulator/rooms/booking.com',
            'live' => 'https://supply-xml.booking.com/hotels/xml/roomrates'
        ],
        'setRate' => [
            'test' => 'https://supply-xml.booking.com/hotels/xml/availability',//TODO add simulator
            'live' => 'https://supply-xml.booking.com/hotels/xml/availability'
        ],
//        'getReservations' => [
//            'test' => 'https://simulator.expediaquickconnect.com/connect/br',
//            'live' => 'https://ws.expediaquickconnect.com/connect/br'
//        ],
//        'setReservationConfirmation' => [
//            'test' => 'https://simulator.expediaquickconnect.com/connect/bc',
//            'live' => 'https://ws.expediaquickconnect.com/connect/bc'
//        ],
    ];

    protected $username = '';//TODO Get it
    protected $password = '';

    /**
     * @param PropertiesChannel $channelSettings
     */
    public function __construct($channelSettings)
    {
        $this->hotelCode = $channelSettings->hotel_code;
    }

    public function getInventoryList()
    {
        $xml = $this->prepareXml();
        $result = $this->processCurl($this->getUrl(__FUNCTION__), $xml);
        if (!$result) {
            return [];
        }
        $inventories = [];
        foreach ($result['obj'] as $one) {
            $inventory = [
                'code' => (string)$one['id'],
                'name' => (string)$one['room_name']
            ];
            foreach ($one->rates->rate as $plan) {
                $inventory['plans'][] = [
                    'code' => (string)$plan['id'],
                    'name' => (string)$plan['rate_name'],
                ];
            }
            $inventories[] = $inventory;
        }

        return $inventories;
    }


    protected function prepareXml($xml = "")
    {

        return
            '<request>' .
            '<username>' . $this->username . '</username>' .
            '<password>' . $this->password . '</password>' .
            '<version>1.0</version>' .
            '<hotel_id>' . $this->hotelCode . '</hotel_id>' . $xml .
            '</request>';
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
            if ($resultObj->fault) {
                foreach ($resultObj->fault as $error) {
                    $errors[] = (string)$error['string'];
                }
            } else {
                $success = true;
            }
        }
        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $result,
            'obj' => $resultObj ? $resultObj : false
        ];
    }

    protected function getUrl($type)
    {
        return $this->urls[$type][$this->getTestMode()];
    }

    /**
     * Set rate on channel
     *
     * @param string $roomId
     * @param string $ratePlanId
     * @param string $fromDate
     * @param string $toDate
     * @param array $days
     * @param float $rate
     * @return mixed
     */
    public function setRate($roomId, $ratePlanId, $fromDate, $toDate, $days, $rate)
    {
        $rightDates = [];


        //adding  Weekdays support to booking.com //TODO move outside of booking.com class
        $fromDateTime = strtotime($fromDate);
        $toDateTime = strtotime($toDate);
        while ($fromDateTime <= $toDateTime) {

            if (in_array(date('N', $fromDateTime) - 1, $days)) {
                $rightDates[] = date('Y-m-d', $fromDateTime);
            }
            $fromDateTime += 86400;
        }

        $rightArray = [];
        $rightArrayCount = 0;
        foreach ($rightDates as $date) {
            if (count($rightArray)) {
                $beforeDate = date('Y-m-d', strtotime($date) - 68400);
                $last = end($rightArray[$rightArrayCount]);
                if ($last != $beforeDate) {
                    $rightArrayCount++;
                }
            }
            $rightArray[$rightArrayCount] [] = $date;
        }


        $xml = '<room id="' . $roomId . '">';

        $dayXml =
            '<rate id="' . $ratePlanId . '"/>' .
            '<price>' . $rate . '</price>' .
            // '<price1>135.00</price1>' TODO implement in bulk form
            '';


        foreach ($rightArray as $period) {
            $lastPeriod = end($period);
            if ($period[0] === $lastPeriod) {
                $xml .= '<date value="' . $lastPeriod . '">';
            } else {
                $xml .= '<date value1="' . $period[0] . '" value2="' . $lastPeriod . '">';
            }
            $xml .= $dayXml . '</date>';
        }

        $xml .= '</room>';

        $xml = $this->prepareXml($xml);
        $result = $this->processCurl($this->getUrl(__FUNCTION__), $xml);

        if ($result['success']) {
            return true;
        }
        return $result['errors'];

    }

    /**
     * Set availability on channel
     *
     * @param string $roomId
     * @param string $fromDate
     * @param string $toDate
     * @param array $days
     * @param integer $availability
     * @return mixed
     */
    public function setAvailability($roomId, $fromDate, $toDate, $days, $availability)
    {
        // TODO: Implement setAvailability() method.
    }

    /**
     * Pull reservation details
     * @return mixed
     */
    public function getReservations()
    {
        // TODO: Implement getReservations() method.
    }

    /**
     * Confirm channel what we receive reservation
     * @param int $reservationId
     * @param string $channelReservationId
     * @param string $reservationType
     * @return mixed
     */
    public function setReservationConfirmation($reservationId, $channelReservationId, $reservationType)
    {
        // TODO: Implement setReservationConfirmation() method.
    }

}