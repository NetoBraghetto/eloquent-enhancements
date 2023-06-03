<?php

namespace Database\Models;

class Region extends AbstractModel
{
    protected $table = 'regions';

    protected $primaryKey = 'id_region';

    protected $fillable = [
        'name',
    ];

    protected $validation_rules = [
        'name' => 'required'
    ];

    public function cities()
    {
        return $this->belongsToMany(City::class, 'regions_cities', 'id_region', 'id_city');
    }
}
