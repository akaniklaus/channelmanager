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
        $rooms = Room::forBulkUpdate(Property::getLoggedId())->get();
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
        $property = Property::findOrFail(Property::getLoggedId());

        foreach ($data['rooms'] as $roomId) {
            //get room data
            $room = Room::findOrFail($roomId);
            $depth = 0;
            $this->updateChannelRate($room, $property, $data, $data['rate'], $weekDays, $errors, $depth);

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
     * Recursive function
     * TODO: move to another place
     * @param Room $room
     * @param Property $property
     * @param $data
     * @param $rate - rate value for update chanel
     * @param $weekDays
     * @param $errors
     * @param $depth
     */
    function updateChannelRate($room, $property, $data, $rate, $weekDays, &$errors, &$depth)
    {
        Log::info($room->name);
        if ($depth > 5) {//infinity loop protection
            return;
        }

        //get plan mapping
        $maps = InventoryMap::getByKeys(null, $property->id, $room->id)->get();
        foreach ($maps as $mapping) {
            //get channel
            $channelSettings = PropertiesChannel::getSettings($mapping->channel_id, $mapping->property_id);
            $channel = ChannelFactory::create($channelSettings);
            $channel->setCurrency($property->currency);
            //updating rates

            $result = $channel->setRate($mapping->inventory_code, $mapping->plan_code, $data['from_date'], $data['to_date'], $weekDays, $rate);
            if (is_array($result)) {
                $formattedErrors = [];
                foreach ($result as $error) {
                    $formattedErrors[] = $channelSettings->channel()->name . ': ' . $error;
                }
                $errors += $formattedErrors;
            }
        }
        //check if children rooms exist
        if ($children = $room->children()->get()) {
            if (!$children->isEmpty()) {
                $depth++;
                //so we go deep so lets do rate of current ROOM as default rate,
                //like if we directly set this rate in form
                $data['rate'] = $rate;
                foreach ($children as $child) {
                    switch ($child->formula_type) {
                        case 'x':
                            $rate = $data['rate'] * $child->formula_value;
                            break;
                        case '+':
                            $rate = $data['rate'] + $child->formula_value;
                            break;
                        case '-':
                            $rate = $data['rate'] - $child->formula_value;
                            break;
                    }
                    $this->updateChannelRate($child, $property, $data, $rate, $weekDays, $errors, $depth);
                }
            }
        }
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