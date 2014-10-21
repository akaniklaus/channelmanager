<?php

class BulkController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /bulk
     *
     * @return Response
     */
    public function getIndex()
    {
        //TODO: move to model after finish, rebuild to full ORM
        $rooms = Room::where('property_id', Property::getLoggedId())->whereExists(function ($query) {
            $query->select(DB::raw(1))->from('inventory_maps as im')
                ->whereRaw('im.room_id = rooms.id AND im.inventory_code is not NULL and im.inventory_code <> ""');
        })->get();
        return View::make('bulk.rates', compact('rooms'));
    }


    public function postUpdateRates()
    {
        $rules = [
            'from_date' => 'required|date_format:Y-m-d|after:' . date('Y-m-d', strtotime('yesterday')),
            'to_date' => 'required|date_format:Y-m-d|after:' . date('Y-m-d', strtotime('yesterday')),
            'week_day' => 'required',
            'rooms' => 'required',
            'rate' => 'required|numeric|min:0',
        ];
        $validator = Validator::make($data = Input::all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ], 400); //400 - http error code
        }

        //all ok so get rooms and plans mapping

        $weekDays = ($data['week_day']);

        $errors = [];

        foreach ($data['rooms'] as $roomId) {
            //get room data
//            $room = Room::findOrFail($roomId);
            //get plan mapping

            $property = Property::findOrFail(Property::getLoggedId());

            $maps = InventoryMap::getByKeys(null, $property->id, $roomId)->get();
            foreach ($maps as $mapping) {
                //get channel
                $channelSettings = PropertiesChannel::getSettings($mapping->channel_id, $mapping->property_id);
                $channel = ChannelFactory::create($channelSettings);
                $channel->setCurrency($property->currency);
                //updating rates
                //TODO rewrite to queue
                $result = $channel->setRate($mapping->inventory_code, $mapping->plan_code, $data['from_date'], $data['to_date'], $weekDays, $data['rate']);
                if (is_array($result)) {
                    $formattedErrors = [];
                    foreach ($result as $error) {
                        $formattedErrors[] = $channelSettings->channel()->name . ': ' . $error;
                    }
                    $errors += $formattedErrors;
                }
//
            }

        }

        if (!empty($errors)) {
            return Response::json([
                'success' => false,
                'errors' => $errors
            ], 400); //400 - http error code
        }


        return Response::json([
            'success' => true,
        ], 200); //200 - http success code
    }

    /**
     * Show the form for creating a new resource.
     * GET /bulk/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /bulk
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /bulk/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /bulk/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /bulk/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /bulk/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}