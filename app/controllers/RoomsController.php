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
//        $result = $channel->getInventoryList();//todo temp
//        file_put_contents('1.txt', serialize($result));
        $result = unserialize(file_get_contents('1.txt'));

        $existMapping = [];
        $mapCollection = InventoryMap::where(
            [
                'channel_id' => $channelId,
                'property_id' => $channelSettings->property_id
            ]
        )->where('room_id', '!=', $id)->get(['inventory_id', 'room_id']);

        foreach ($mapCollection as $map) {
            $existMapping[] = $map->inventory_id;
        }
        $inventoryList = ['' => 'Unmapped'];
        foreach ($result as $inventory) {
            if (in_array($inventory['id'], $existMapping)) {
                continue;
            }
            $inventoryList[$inventory['id']] = $inventory['name'];
        }

        $inventoryPlans = [];
        if ($inventory['plans']) {
            foreach ($result as $inventory) {
                if (in_array($inventory['id'], $existMapping)) {
                    continue;
                }
                foreach ($inventory['plans'] as $plan) {
                    $inventoryPlans[$inventory['id']][] = $plan;
                }
            }
        }
        $inventoryPlans = json_encode($inventoryPlans);

        $mapping = InventoryMap::getMapping($id, $channelId, $channelSettings->property_id);


        return View::make('rooms.map', compact('room', 'channel', 'inventoryList', 'inventoryPlans', 'channelId', 'mapping'));
    }

    /**
     * Update the specified room in storage.
     *
     * @return Response
     */
    public function postMap()
    {

        $validator = Validator::make($data = Input::all(), InventoryMap::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $data['property_id'] = Property::getLoggedId();

        $mapping = InventoryMap::getMapping($data['room_id'], $data['channel_id'], $data['property_id'], false);

        //get inventory room name
        if ($data['inventory_id']) {//TODO: find more fast way, the same as PIVOT below
            $channel = ChannelFactory::create($data['channel_id']);
            $channelSettings = PropertiesChannel::getSettings($data['channel_id'], Property::getLoggedId());
            $channel->setHotelCode($channelSettings->hotel_code);
//        $result = $channel->getInventoryList();//todo temp
//        file_put_contents('1.txt', serialize($result));
            $result = unserialize(file_get_contents('1.txt'));
            foreach ($result as $one) {
                if ($one['id'] == $data['inventory_id']) {
                    $data['name'] = $one['name'];
                    break;
                }
            }
        } else {
            $data['name'] = '';
        }

        if ($mapping && $mapping->first()) {//TODO rewrite to PIVOT ?
            if (isset($data['_token'])) {
                unset($data['_token']);
            }
            $mapping->update($data);
        } else {
            InventoryMap::create($data);
        }


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
