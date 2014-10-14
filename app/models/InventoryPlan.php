<?php

class InventoryPlan extends \Eloquent
{
    protected $primaryKey = ['channel_id', 'inventory_code', 'code', 'property_id'];
    public $incrementing = false;

    protected $fillable = ['name', 'code', 'channel_id', 'inventory_code', 'property_id'];
}