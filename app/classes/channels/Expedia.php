<?php namespace Channels;

use Illuminate\Support\Facades\Log;

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


    protected $returnWarnings = 1;
    protected $hotelCode;

    protected $urls = [
        'getInventoryList' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/parr',
            'live' => 'https://ws.expediaquickconnect.com/connect/parr'
        ],
        'setRate' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/ar',
            'live' => 'https://ws.expediaquickconnect.com/connect/ar'
        ],
        'getReservations' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/br',
            'live' => 'https://ws.expediaquickconnect.com/connect/br'
        ],
        'setReservationConfirmation' => [
            'test' => 'https://simulator.expediaquickconnect.com/connect/bc',
            'live' => 'https://ws.expediaquickconnect.com/connect/bc'
        ],
    ];

    protected $ns = [
        'PAR' => 'http://www.expediaconnect.com/EQC/PAR/2013/07',//getInventoryList
        'AR' => 'http://www.expediaconnect.com/EQC/AR/2011/06',//setAvailability
        'BR' => 'http://www.expediaconnect.com/EQC/BR/2014/01',//getReservations
        'BC' => 'http://www.expediaconnect.com/EQC/BC/2007/09'//setReservationConfirmation
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

    /**
     * @param string $roomId
     * @param string $ratePlanId
     * @param string $fromDate
     * @param string $toDate
     * @param array $days
     * @param float $rate
     * @return bool|mixed
     */
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

    /**
     * @param string $roomId
     * @param string $fromDate
     * @param string $toDate
     * @param array $days
     * @param int $availability
     * @return bool|mixed
     */
    public function setAvailability($roomId, $fromDate, $toDate, $days, $availability)
    {
        $xml = '<AvailRateUpdate>' .
            '<DateRange from="' . $fromDate . '" to="' . $toDate . '" ' . $this->getWeekDaysStr($days) . '/>' .
            '<RoomType id="' . $roomId . '">' .
            '<Inventory totalInventoryAvailable="' . $availability . '"/>' .
            '</RoomType>' .
            '</AvailRateUpdate>';
        $xml = $this->prepareXml('AvailRateUpdate', $this->ns['AR'], $xml);
        $result = $this->processCurl($this->getUrl('setRate'), $xml);
        if ($result['success']) {
            return true;
        }
        return $result['errors'];
    }

    /**
     * Pull reservation details
     * @return mixed
     */
    public function getReservations()
    {
        $xml = $this->prepareXml('BookingRetrieval', $this->ns['BR']);
        $answer = $this->processCurl($this->getUrl(__FUNCTION__), $xml);
        if ($answer['success']) {
            if ($answer['obj']) {
                $reservations = [];
                foreach ($answer['obj']->Bookings->Booking as $one) {
                    $reservation = [];
                    $reservation['res_id'] = (string)$one['id'];
                    switch ((string)$one['type']) {
                        case 'Book':
                            $reservation['status'] = 'booked';
                            break;
                        case 'Modify':
                            $reservation['status'] = 'booked';
                            $reservation['modified'] = 1;
                            break;
                        case 'Cancel':
                            $reservation['status'] = 'cancelled';
                            break;
                    }

                    $reservation['res_created'] = str_ireplace(['T', 'Z'], [' ', ''], (string)$one['createDateTime']);
                    $reservation['res_source'] = (string)$one['source'];

                    $reservation['guest_firstname'] = (string)$one->PrimaryGuest->Name['givenName'];
                    $reservation['guest_lastname'] = (string)$one->PrimaryGuest->Name['surname'];
                    $reservation['email'] = (string)$one->PrimaryGuest->Email;

                    $reservation['phone'] = '';//format 1.111.111-111-111.11
                    if ($one->PrimaryGuest->Phone['countryCode']) {
                        $reservation['phone'] .= $one->PrimaryGuest->Phone['countryCode'] . '.';
                    }
                    if ($one->PrimaryGuest->Phone['cityAreaCode']) {
                        $reservation['phone'] .= $one->PrimaryGuest->Phone['cityAreaCode'] . '.';
                    }
                    if ($one->PrimaryGuest->Phone['number']) {
                        $reservation['phone'] .= $one->PrimaryGuest->Phone['number'];
                    }
                    if ($one->PrimaryGuest->Phone['extension']) {
                        $reservation['phone'] .= '.' . $one->PrimaryGuest->Phone['extension'];
                    }

                    $reservation['comments'] = '';
                    if ($one->SpecialRequest) {
                        $specials = [];
                        foreach ($one->SpecialRequest as $special) {
                            $specials[] = (string)$special;
                        }
                        $reservation['comments'] = implode(', ', $specials);
                    }


                    $stay = $one->RoomStay;
                    $reservation['res_inventory'] = (string)$stay['roomTypeID'];
                    $reservation['res_plan'] = (string)$stay['ratePlanID'];

                    $reservation['date_arrival'] = (string)$stay->StayDate['arrival'];
                    $reservation['date_departure'] = (string)$stay->StayDate['departure'];

                    $reservation['count_adult'] = (int)$stay->GuestCount['adult'];
                    $reservation['count_child'] = (int)$stay->GuestCount['child'];
                    if ($reservation['count_child'] && $stay->GuestCount->Child) {
                        $reservation['count_child_age'] = (int)$stay->GuestCount->Child['age'];
                    }

                    $reservation['total'] = number_format((string)$stay->Total['amountAfterTaxes'], 2);
                    $reservation['currency'] = (string)$stay->Total['currency'];

                    if ($ccInfo = $stay->PaymentCard) {
                        $ccTypes = ['AX' => 'American express', 'DS' => 'Discovery card', 'VI' => 'Visa', 'MC' => 'Master card'];
                        $ccType = (string)$ccInfo['cardCode'];
                        $ccType = isset($ccTypes[$ccType]) ? $ccTypes[$ccType] : $ccType;

                        $reservation['cc_details'] = [
                            "card_type" => $ccType,
                            "card_number" => (string)$ccInfo['cardNumber'],
                            "card_name" => (string)$ccInfo->CardHolder['name'],
                            "card_address" => (string)$ccInfo->CardHolder['address'],
                            "card_city" => (string)$ccInfo->CardHolder['city'],
                            "card_country" => (string)$ccInfo->CardHolder['country'],
                            "card_postal_code" => (string)$ccInfo->CardHolder['postalCode'],
                            "card_state" => (string)$ccInfo->CardHolder['stateProv'],
                            "card_expiry_month" => substr($ccInfo['expireDate'], 0, 2),
                            "card_expiry_year" => substr($ccInfo['expireDate'], 2, 2),
                            "card_cvv" => (string)$ccInfo['seriesCode']
                        ];
                    }


                    $reservations[] = $reservation;

                }
                return compact('reservations');

            }
        }
        return $answer['errors'];
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
        \Log::info(__METHOD__);
        \Log::debug(func_get_args());
        switch ($reservationType) {
            case 'modify':
                $type = 'Modify';
                break;
            case 'cancel':
                $type = 'Cancel';
                break;
            default :
                $type = 'Book';
        }
        $xml =
            '<BookingConfirmNumbers>' .
            '<BookingConfirmNumber bookingID="' . $channelReservationId . '" bookingType="' . $type . '" confirmNumber="' . $reservationId . '" confirmTime="' . gmdate(DATE_ATOM) . '"/>' .
            '</BookingConfirmNumbers>';
        $xml = $this->prepareXml('BookingConfirm', $this->ns['BC'], $xml);
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
            } elseif ($this->returnWarnings && $resultObj->Success && $resultObj->Success->Warning) {
                foreach ($resultObj->Success->Warning as $warning) {
                    $errors[] = 'Warning: ' . (string)$warning;
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