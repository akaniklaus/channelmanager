<?php

class ReservationsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /reservations
     *
     * @return Response
     */
    public function getIndex()
    {
        $execResult = [
            'updated' => 0,
            'created' => 0,
            'bookings' => 0,
            'cancelled' => 0,
            'not_mapped' => 0
        ];

        $propertiesChannels = PropertiesChannel::where('property_id', Property::getLoggedId())->get();
        foreach ($propertiesChannels as $channelSettings) {
            Log::debug($channelSettings);
            $channel = ChannelFactory::create($channelSettings);
            $result = $channel->getReservations();
            Log::debug($result);
            if ($result['reservations']) {
                foreach ($result['reservations'] as $reservation) {
                    $reservation['channel_id'] = $channelSettings->channel_id;
                    $reservation['property_id'] = $channelSettings->property_id;

                    $resModel = Reservation::getByKeys($channelSettings->channel_id, $channelSettings->property_id)
                        ->where('res_id', $reservation['res_id'])->first();

                    if (isset($reservation['cc_details']) && !empty($reservation['cc_details'])) {
                        $reservation['cc_details'] = Crypt::encrypt($reservation['cc_details']);
                    }

                    switch ($reservation['status']) {
                        case 'cancelled':
                            if ($resModel) {
                                $resModel->status = 'cancelled';
                                if ($reservation['res_cancel_fee']) {
                                    $resModel->res_cancel_fee = $reservation['res_cancel_fee'];
                                }
                                $resModel->cancelled_at = $resModel->freshTimestamp();
                                $resModel->save();
                                $execResult['cancelled']++;
                                //TODO: send email about cancellation
                            }
                            break;
                        case 'booked':
                            $needAddRooms = true;
                            if ($resModel) {
                                if (isset($reservation['modified']) && $reservation['modified']) {
                                    $resModel->update($reservation);
                                    $execResult['updated']++;

                                    $resModel->bookings()->delete();
                                    //TODO: send email about modification
                                } else {
                                    $needAddRooms = false;
                                }
                            } else {
                                $resModel = Reservation::create($reservation);
                                $execResult['created']++;
                            }

                            if ($reservation['rooms'] && $needAddRooms) {
                                foreach ($reservation['rooms'] as $room) {
                                    $room['reservation_id'] = $resModel->id;
                                    $room['channel_id'] = $reservation['channel_id'];
                                    $room['property_id'] = $reservation['property_id'];
                                    $mapping = InventoryMap::getMappedRoom(
                                        $channelSettings->channel_id, $channelSettings->property_id,
                                        $room['inventory'], isset($room['plan']) ? $room['plan'] : null
                                    )->first();
                                    if ($mapping) {
                                        $room['room_id'] = $mapping->room_id;
                                    } else {
                                        $execResult['not_mapped']++;
                                        //TODO: send email about NOT MAPPED ROOM
                                    }
                                    Booking::create($room);
                                    $execResult['bookings']++;
                                }
                            }
                            break;
                    }

                    if ($resModel && $resModel->id) {
                        $type = $resModel->status;
                        if ($type != 'cancelled' && $resModel->modified) {
                            $type = 'modify';
                        }
                        $channel->setReservationConfirmation($resModel->id, $resModel->res_id, $type);
                    }
                }
            }
        }

        return View::make('index', compact('execResult'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /reservations/create
     *
     * @return Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /reservations
     *
     * @return Response
     */
    public
    function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /reservations/{id}
     *
     * @param  int $id
     * @return Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /reservations/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public
    function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /reservations/{id}
     *
     * @param  int $id
     * @return Response
     */
    public
    function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /reservations/{id}
     *
     * @param  int $id
     * @return Response
     */
    public
    function destroy($id)
    {
        //
    }

}