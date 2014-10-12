<?php

class RoomsController extends \BaseController
{

    /**
     * Display a listing of rooms
     *
     * @return Response
     */
    public function getIndex()
    {
        $rooms = Room::where('property_id', Property::getLoggedId())->get();

        $channels = PropertiesChannel::where('property_id', Property::getLoggedId())->get();

        return View::make('rooms.index', compact('rooms', 'channels'));
    }

    /**
     * Show the form for creating a new room
     *
     * @return Response
     */
    public function getCreate()
    {
        $properties = Property::lists('name', 'id');

        return View::make('rooms.create', compact('properties'));
    }

    /**
     * Store a newly created room in storage.
     *
     * @return Response
     */
    public function postStore()
    {
        $validator = Validator::make($data = Input::all(), Room::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $data['property_id'] = Property::getLoggedId();

        Room::create($data);

        return Redirect::action('RoomsController@getIndex');
    }

    /**
     * Display the specified room.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $room = Room::findOrFail($id);

        return View::make('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     *
     * @param  int $id
     * @return Response
     */
    public function getEdit($id)
    {
        $room = Room::find($id);

        return View::make('rooms.edit', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     *
     * @param int $id
     * @param int $channelId
     * @return Response
     */
    public function getMap($id, $channelId)
    {
        $room = Room::find($id);

        $channelSettings = PropertiesChannel::getSettings($channelId, Property::getLoggedId());

        $channel = ChannelFactory::create($channelId);
        $channel->setHotelCode($channelSettings->hotel_code);
        $result = $channel->getInventoryList();
        $inventoryList = [];
        foreach ($result as $inventory) {
            $inventoryList[$inventory['id']] = $inventory['name'];
        }

        $inventoryPlans = [];
        if ($inventory['plans']) {
            foreach ($result as $inventory) {
                foreach ($inventory['plans'] as $plan) {
                    $inventoryPlans[$inventory['id']] = $plan;
                }
            }
        }


        return View::make('rooms.map', compact('room', 'channel', 'inventoryList', 'inventoryPlans'));
    }

    /**
     * Update the specified room in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postMap($id)
    {
        $room = Room::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Room::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $room->update($data);

        return Redirect::action('RoomsController@getIndex');
    }

    /**
     * Update the specified room in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postUpdate($id)
    {
        $room = Room::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Room::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $room->update($data);

        return Redirect::action('RoomsController@getIndex');
    }

    /**
     * Remove the specified room from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function getDestroy($id)
    {
        Room::destroy($id);

        return Redirect::action('RoomsController@getIndex');
    }

}
