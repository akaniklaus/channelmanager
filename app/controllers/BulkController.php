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
        $rooms = Room::where('property_id', Property::getLoggedId())->get();
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