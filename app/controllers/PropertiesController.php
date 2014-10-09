<?php

class PropertiesController extends \BaseController
{


    /**
     * Switch current property
     * @var $id int
     */
    public function getSwitch()
    {
        Session::put(Property::PROPERTY_ID, \Illuminate\Support\Facades\Input::get('property_id'));
        return Redirect::back();
    }

    /**
     * Display a listing of properties
     *
     * @return Response
     */
    public function getIndex()
    {
        $properties = Property::all();

        return View::make('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('properties.create');
    }

    /**
     * Store a newly created property in storage.
     *
     * @return Response
     */
    public function postStore()
    {
        $validator = Validator::make($data = Input::all(), Property::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Property::create($data);

        return Redirect::action('PropertiesController@getIndex');
    }

    /**
     * Display the specified property.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);

        return View::make('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     *
     * @param  int $id
     * @return Response
     */
    public function getEdit($id)
    {
        $property = Property::find($id);

        return View::make('properties.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postUpdate($id)
    {
        $property = Property::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Property::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $property->update($data);

        return Redirect::action('PropertiesController@getIndex');
    }

    /**
     * Remove the specified property from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function getDestroy($id)
    {
        Property::destroy($id);

        return Redirect::action('PropertiesController@getIndex');
    }

}
