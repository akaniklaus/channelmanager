<?php

/**
 * TODO: add negative result cases
 *
 * Class SimulatorController
 */
class SimulatorController extends \BaseController
{
    public function postReservations($channelName, $type = null)
    {
        return $this->getReservations($channelName, $type);
    }

    public function getReservations($channelName, $type = null)
    {
        $result = '';
        switch ($channelName) {
            case 'booking.com':
                switch ($type) {
                    case 'confirmation':
                        $result = <<<'EOD'
<reservations>
<reservation>
<commissionamount>45</commissionamount>
<currencycode>EUR</currencycode>
<customer>
<address>ADDRESS</address>
<cc_cvc></cc_cvc>
<cc_expiration_date>01/2022</cc_expiration_date>
<cc_name>CARD HOLDER NAME</cc_name>
<cc_number>5299337533617341</cc_number>
<cc_type>MasterCard</cc_type>
<city>CITY</city>
<company></company>
<countrycode>nl</countrycode>
<dc_issue_number></dc_issue_number>
<dc_start_date></dc_start_date>
<email></email>
<first_name>FIRSTNAMEBOOKER</first_name>
<last_name>LASTNAMEBOOKER</last_name>
<remarks>SPECIAL REQUESTS</remarks>
<telephone>+31 20 715 00 00</telephone>
<zip>ZIP CODE</zip>
</customer>
<date>2015-11-13</date>
<hotel_id>367456</hotel_id>
<hotel_name>XML test hotel</hotel_name>
<id>312651462</id>
<loyalty_id>123ABC</loyalty_id>
<room>
<addons>
<addon>
<name>Parking space</name>
<nights>1</nights>
<persons>2</persons>
<price_mode>3</price_mode>
<price_per_unit>5</price_per_unit>
<totalprice>5</totalprice>
<type>22</type>
</addon>
</addons>
<arrival_date>2015-12-13</arrival_date>
<commissionamount>45</commissionamount>
<currencycode>EUR</currencycode>
<departure_date>2015-12-14</departure_date>
<extra_info></extra_info>
<facilities>Wooden / Parquet floor</facilities>
<guest_name>FIRSTNAMEBOOKER LASTNAMEBOOKER</guest_name>
<id>36745601</id>
<info>All meals and select beverages are included in the room rate. Children and Extra Bed Policy: All children are welcome. All children under 1 years stay free of charge when using existing beds. All children under 2 years are charged EUR 20 per person per night for children's cots/cribs. There is no capacity for extra beds in the room. The maximum number of children's cots/cribs in a room is 1.  Deposit Policy: No deposit will be charged.  Cancellation Policy: If cancelled  up to 4 days before date of arrival,  no fee will be charged. If cancelled  later or in case of no-show, the total price of the reservation will be charged.</info>
<max_children>0</max_children>
<meal_plan>All meals and select beverages are included in the room rate.</meal_plan>
<name>Standard Double Room - Special conditions</name>
<numberofguests>2</numberofguests>
<price date="2015-12-13"
             rate_id="1278608">370</price>
<remarks></remarks>
<roomreservation_id>243197707</roomreservation_id>
<smoking></smoking>
<totalprice>375</totalprice>
</room>
<status>new</status>
<time>10:44:46</time>
<totalprice>375</totalprice>
</reservation>
</reservations>
<!-- RUID: [UmFuZG9tSVYkc2RlIyh9YUMeu8mgftUJZxbYXNvKZS4EHi5PYAFc0x82U1v1VU9Dzb4Z8JBQfyRCX2WGnqFhLg==] -->
EOD;
                        break;
                    case 'modification':
                        $result = <<<'EOD'
<reservations>
<reservation>
<commissionamount>45</commissionamount>
<currencycode>EUR</currencycode>
<customer>
<address>ADDRESS</address>
<cc_cvc></cc_cvc>
<cc_expiration_date></cc_expiration_date>
<cc_name></cc_name>
<cc_number></cc_number>
<cc_type></cc_type>
<city>CITY</city>
<company></company>
<countrycode>nl</countrycode>
<dc_issue_number></dc_issue_number>
<dc_start_date></dc_start_date>
<email></email>
<first_name>FIRSTNAMEBOOKER</first_name>
<last_name>LASTNAMEBOOKER</last_name>
<remarks>SPECIAL REQUESTS</remarks>
<telephone>+31 20 715 00 00</telephone>
<zip>ZIP CODE</zip>
</customer>
<date>2012-11-13</date>
<hotel_id>367456</hotel_id>
<hotel_name>XML test hotel</hotel_name>
<id>312651462</id>
<loyalty_id>123ABC</loyalty_id>
<room>
<addons>
<addon>
<name>Parking space</name>
<nights>1</nights>
<persons>1</persons>
<price_mode>3</price_mode>
<price_per_unit>5</price_per_unit>
<totalprice>5</totalprice>
<type>22</type>
</addon>
</addons>
<arrival_date>2012-12-13</arrival_date>
<commissionamount>45</commissionamount>
<currencycode>EUR</currencycode>
<departure_date>2012-12-14</departure_date>
<extra_info></extra_info>
<facilities>Wooden / Parquet floor</facilities>
<guest_name>MODIFIED GUEST NAME</guest_name>
<id>36745601</id>
<info>All meals and select beverages are included in the room rate. Children and Extra Bed Policy: All children are welcome. All children under 1 years stay free of charge when using existing beds. All children under 2 years are charged EUR 20 per person per night for children's cots/cribs. There is no capacity for extra beds in the room. The maximum number of children's cots/cribs in a room is 1.  Deposit Policy: No deposit will be charged.  Cancellation Policy: If cancelled  up to 4 days before date of arrival,  no fee will be charged. If cancelled  later or in case of no-show, the total price of the reservation will be charged.</info>
<max_children>0</max_children>
<meal_plan>All meals and select beverages are included in the room rate.</meal_plan>
<name>Standard Double Room - Special conditions</name>
<numberofguests>1</numberofguests>
<price date="2012-12-13"
             rate_id="1278608">370</price>
<remarks></remarks>
<roomreservation_id>243197707</roomreservation_id>
<smoking>0</smoking>
<totalprice>375</totalprice>
</room>
<status>modified</status>
<time>10:44:46</time>
<totalprice>375</totalprice>
</reservation>
</reservations>
<!-- RUID: [UmFuZG9tSVYkc2RlIyh9YUMeu8mgftUJplzMqf3R5wJsKIeEj/WPNky6YFxl8beX9QhX22p91ygRv1RLtszpgw==] -->
EOD;
                        break;
                    case 'cancellation':
                        $result = <<<'EOD'
<reservations>
<reservation>
<commissionamount></commissionamount>
<currencycode>EUR</currencycode>
<customer>
<address>efthdrtfhdfg</address>
<cc_cvc></cc_cvc>
<cc_expiration_date></cc_expiration_date>
<cc_name></cc_name>
<cc_number></cc_number>
<cc_type></cc_type>
<city>hdfhgdfgh</city>
<company></company>
<countrycode>gb</countrycode>
<dc_issue_number></dc_issue_number>
<dc_start_date></dc_start_date>
<email></email>
<first_name>test</first_name>
<last_name>test</last_name>
<remarks>test</remarks>
<telephone>425634563465</telephone>
<zip>3653546</zip>
</customer>
<date>2013-01-14</date>
<hotel_id>464167</hotel_id>
<hotel_name>Steve's Sunny hotel</hotel_name>
<id>312651462</id>
<status>cancelled</status>
<time>14:30:46</time>
<total_cancellation_fee>323</total_cancellation_fee>
<totalprice>0</totalprice>
</reservation>
</reservations>
<!-- RUID: [UmFuZG9tSVYkc2RlIyh9YScAAH0Bzh23eh4iMYGUj3+OwYOITRSCscllxLuwfvvePWuyVRiPIYiL/EZDUK3akw==] -->
EOD;
                        break;
                    default:
                        $result = <<<'EOD'
<reservations /><!-- RUID: [UmFuZG9tSVYkc2RlIyh9YQGcRIMHryJSA8r+sODrfrI11L6d1gSdXeQIWR+JUqw7jiueqxiOEPqYAFoNTD2eUQ==] -->
EOD;
                        break;
                }

                break;
        }
        return $this->response($result);
    }

    public function postAvailability($channelName)
    {
        return $this->getAvailability($channelName);
    }

    public function getAvailability($channelName)
    {
        $result = '';
        switch ($channelName) {
            case 'booking.com':
                $result = <<<'EOD'
<?xml version='1.0' standalone='yes'?>
<ok/><!-- RUID: [UmFuZG9tSVYkc2RlIyh9YQ73DFUUFi6McMhe033LZV4drvQuskco7bV0zLkbkbxM4PPLzEpHagZLvIM5j8tBZw==] -->
EOD;

                break;
        }

        return $this->response($result);
    }

    public function postRooms($channelName)
    {
        return $this->getRooms($channelName);
    }

    public function getRooms($channelName)
    {
        $result = '';
        switch ($channelName) {
            case 'booking.com':
                $result = <<<'EOD'
<?xml version='1.0' standalone='yes'?>
<rooms>
<room id="46416701"
        hotel_id="464167"
        hotel_name="Steve's Sunny hotel"
        max_children="0"
        room_name="Standard Double Room with Sea View">
<rates>
<rate id="1590909"
            is_child_rate="1"
            max_persons="2"
            policy="General"
            policy_id="82024976"
            rate_name="DEAL89"
            readonly="1" />
<rate id="1592191"
            is_child_rate="1"
            max_persons="2"
            policy="Non Refundable"
            policy_id="82024984"
            rate_name="Non-refundable" />
<rate id="1578444"
            max_persons="2"
            policy="General"
            policy_id="82024976"
            rate_name="Standard Rate" />
<rate id="1589701"
            is_child_rate="1"
            max_persons="2"
            policy="General"
            policy_id="82024976"
            rate_name="DEAL71"
            readonly="1" />
</rates>
</room>
<room id="46416702"
        hotel_id="464167"
        hotel_name="Steve's Sunny hotel"
        max_children="0"
        room_name="Superior Studio">
<rates>
<rate id="1590909"
            is_child_rate="1"
            max_persons="4"
            policy="General"
            policy_id="82024976"
            rate_name="DEAL89"
            readonly="1" />
<rate id="1592191"
            is_child_rate="1"
            max_persons="4"
            policy="Non Refundable"
            policy_id="82024984"
            rate_name="Non-refundable" />
<rate id="1578444"
            max_persons="4"
            policy="General"
            policy_id="82024976"
            rate_name="Standard Rate" />
<rate id="1589701"
            is_child_rate="1"
            max_persons="4"
            policy="General"
            policy_id="82024976"
            rate_name="DEAL71"
            readonly="1" />
</rates>
</room>
<room id="46416703"
        hotel_id="464167"
        hotel_name="Steve's Sunny hotel"
        max_children="0"
        room_name="Standard Room">
<rates>
<rate id="1590909"
            is_child_rate="1"
            max_persons="2"
            policy="General"
            policy_id="82024976"
            rate_name="DEAL89"
            readonly="1" />
<rate id="1592191"
            is_child_rate="1"
            max_persons="2"
            policy="Non Refundable"
            policy_id="82024984"
            rate_name="Non-refundable" />
<rate id="1578444"
            max_persons="2"
            policy="General"
            policy_id="82024976"
            rate_name="Standard Rate" />
<rate id="1589701"
            is_child_rate="1"
            max_persons="2"
            policy="General"
            policy_id="82024976"
            rate_name="DEAL71"
            readonly="1" />
</rates>
</room>
</rooms>
<!-- RUID: [UmFuZG9tSVYkc2RlIyh9YXIOi0hY73GH9H+h2JUKAGDKUh+/68J9jOrgkmN45UXIaNe9s8vuwB6CnfOyv+mQ1A==] -->
EOD;

                break;
        }

        return $this->response($result);
    }

    protected function response($result)
    {
        die($result);
    }

}