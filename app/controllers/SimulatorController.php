<?php

/**
 * TODO: add negative result cases
 *
 * Class SimulatorController
 */
class SimulatorController extends \BaseController
{
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