<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    use HasFactory;

    protected $fillable = [
        'visite_counter',
    ];

    public function images()
    {
        return $this->hasMany('App\Models\ItemImage');
    }

    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function data_carrier()
    {
        return $this->hasOne('App\Models\DataCarrier', 'id', 'data_carrier_id');
    }

    public function styles()
    {
        return $this->hasMany('App\Models\MusicStyleRelation', 'product_id', 'id')
            ->select('style_id');
    }

    public function publisher()
    {
        return $this->hasOne('App\Models\Publisher', 'id', 'publisher_id')
            ->select('name');
    }

    public function wrapper()
    {
        return $this->hasOne('App\Models\Wrapper', 'id', 'wrapper_id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }
}

