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
        $rooms = Room::all();

        return View::make('rooms.index', compact('rooms'));
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
