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
        'setRate' => [ //also: setAvailability
            'test' => 'http://cm.kamer.ngrok.com/simulator/availability/booking.com',
            'live' => 'https://supply-xml.booking.com/hotels/xml/availability'
        ],
        'getReservations' => [
//            'test' => 'http://cm.kamer.ngrok.com/simulator/reservations/booking.com',
//            'test' => 'http://cm.kamer.ngrok.com/simulator/reservations/booking.com/confirmation',
//            'test' => 'http://cm.kamer.ngrok.com/simulator/reservations/booking.com/modification',
            'test' => 'http://cm.kamer.ngrok.com/simulator/reservations/booking.com/cancellation',
            'live' => 'https://secure-supply-xml.booking.com/hotels/xml/reservations'
        ],
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
     * Adding  Weekdays support to booking.com
     * //TODO move outside of booking.com class
     * @param $fromDate
     * @param $toDate
     * @param $days
     * @return array
     */
    protected function getDatePeriodsForUpdate($fromDate, $toDate, $days)
    {
        $rightDates = [];
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
        return $rightArray;
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
     * @param float $rate1
     * @return mixed
     */
    public function setRate($roomId, $ratePlanId, $fromDate, $toDate, $days, $rate, $rate1 = null)
    {
        $xml = '<room id="' . $roomId . '">';

        $dayXml =
            '<rate id="' . $ratePlanId . '"/>' .
            '<price>' . $rate . '</price>';
        if ($rate1 && $rate1 > 0) {
            $dayXml .= '<price1>' . $rate1 . '</price1>';
        }

        $rightArray = $this->getDatePeriodsForUpdate($fromDate, $toDate, $days);

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
        $xml = '<room id="' . $roomId . '">';

        $dayXml =
            '<roomstosell>' . $availability . '</roomstosell>' .
            '';

        $rightArray = $this->getDatePeriodsForUpdate($fromDate, $toDate, $days);

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
        $xml = $this->prepareXml();
        $answer = $this->processCurl($this->getUrl(__FUNCTION__), $xml);
        if ($answer['success']) {
            $reservations = [];
            if ($answer['obj']) {
                foreach ($answer['obj']->reservation as $one) {
                    $reservation['res_id'] = (string)$one->id;

                    switch ((string)$one->status) {
                        case 'new':
                            $reservation['status'] = 'booked';
                            break;
                        case 'modified':
                            $reservation['status'] = 'booked';
                            $reservation['modified'] = 1;
                            break;
                        case 'cancelled':
                            $reservation['status'] = 'cancelled';
                            if ($one->total_cancellation_fee) {
                                $reservation['res_cancel_fee'] = number_format((string)$one->total_cancellation_fee, 2);
                            }
                            $reservations[] = $reservation;
                            continue 2;
                            break;
                    }

                    $reservation['res_created'] = (string)$one->date . ' ' . (string)$one->time;
                    $reservation['res_source'] = 'Booking.com';
                    if ($one->loyalty_id) {
                        $reservation['res_loyalty_id'] = (string)$one->loyalty_id;
                    }
                    $reservation['commission'] = number_format((string)$one->commissionamount, 2);
                    $reservation['buyer_firstname'] = (string)$one->customer->first_name;
                    $reservation['buyer_lastname'] = (string)$one->customer->last_name;
                    $reservation['email'] = (string)$one->customer->email;
                    $reservation['phone'] = (string)$one->customer->telephone;

                    $reservation['total'] = number_format((string)$one->totalprice, 2);
                    $reservation['currency'] = (string)$one->currencycode;


                    if ($one->customer->remarks) {
                        $reservation['comments'] = (string)$one->customer->remarks;

                        //genius
                        if (strpos($reservation['comments'], '*** Genius booker ***') !== false) {
                            if (!$reservation['res_loyalty_id']) {
                                $reservation['res_loyalty_id'] = '';
                            }
                            $reservation['res_loyalty_id'] .= '*** Genius booker ***';
                            $reservation['comments'] = str_replace('*** Genius booker ***', '', $reservation['comments']);
                        }
                    }

                    if ($one->customer->cc_number) {
                        $reservation['cc_details'] = [
                            "card_type" => (string)$one->customer->cc_type,
                            "card_number" => (string)$one->customer->cc_number,
                            "card_name" => (string)$one->customer->cc_name,
                            "card_expiry_month" => substr((string)$one->customer->cc_expiration_date, 0, 2),
                            "card_expiry_year" => substr((string)$one->customer->cc_expiration_date, -2, 2),
                            "card_cvv" => (string)$one->customer->cc_cvc,
                            "dc_issue_number" => (string)$one->customer->dc_issue_number,
                            "dc_start_date" => (string)$one->customer->dc_start_date,
                        ];
                    }

                    $reservation["address"] = (string)$one->customer->address;
                    $reservation["city"] = (string)$one->customer->city;
                    $reservation["country"] = (string)$one->customer->countrycode;
                    $reservation["postal_code"] = (string)$one->customer->zip;
                    $reservation["state"] = '';

                    $reservation['rooms'] = [];
                    if ($one->room) {
                        foreach ($one->room as $room) {
                            $rrRoom = [];
                            $rrRoom['rr_id'] = (string)$room->id;
                            $rrRoom['inventory'] = (string)$room->roomreservation_id;
                            $rrRoom['plan'] = (string)$room->price['rate_id'];
                            foreach ($room->price as $price) {
                                $rrRoom['prices'][] = (string)$price;
                            }
                            if ($rrRoom['prices']) {
                                $rrRoom['prices'] = implode(',', $rrRoom['prices']);
                            }
                            $rrRoom['commission'] = number_format((string)$room->commissionamount, 2);
                            $rrRoom['currency'] = (string)$room->currencycode;
                            $rrRoom['total'] = number_format((string)$room->totalprice, 2);

                            $reservation['date_arrival'] = (string)$room->arrival_date;
                            $reservation['date_departure'] = (string)$room->departure_date;

                            $str = trim($room->guest_name);
                            if ($str != "") {
                                $str = trim(preg_replace("/[[:blank:]]+/", " ", $str));
                                list($firstName, $lastName) = explode(" ", $str, 2);
                            }

                            if (!$firstName || !$lastName) {
                                $firstName = (string)$one->customer->first_name;
                                $lastName = (string)$one->customer->last_name;
                            }
                            $rrRoom['guest_firstname'] = $firstName;
                            $rrRoom['guest_lastname'] = $lastName;

                            $rrRoom['count_adult'] = (int)$room->numberofguests;
                            $rrRoom['count_child'] = 0;

                            $comments = '';
                            if ($room->addons) {
                                foreach ($room->addons->addon as $addon) {
                                    $comments .= "- " . $room->currencycode . ' ' . number_format((string)$addon->totalprice, 2) . ' ' . $addon->name . ' for ' . $addon->nights . ' night(s) and ' . $addon->persons . ' person(s)' . "\n";
                                }
                            }
                            if (!empty($comments)) {
                                $comments = "Add-ons:\n" . $comments . "\n";
                            }
                            $comments .= "Smoking room: " . ($room->smoking == 0 ? "No" : "Yes") . "\n";
                            if ($room->remarks) {
                                $comments .= (string)$room->remarks;
                            }

                            $rrRoom['comments'] = $comments;

                            $reservation['rooms'][] = $rrRoom;
                        }
                    }

                    $reservations[] = $reservation;

                }
            }
            return compact('reservations');
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
        // TODO: Implement setReservationConfirmation() method.
    }

}