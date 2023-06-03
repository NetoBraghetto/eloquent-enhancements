<?php

namespace Database\Models;

class City extends AbstractModel
{
    protected $table = 'cities';

    protected $primaryKey = 'id_city';

    protected $fillable = [
        'name',
    ];

    protected $validation_rules = [
        'name' => 'required'
    ];

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'regions_cities', 'id_city', 'id_region');
    }
}
