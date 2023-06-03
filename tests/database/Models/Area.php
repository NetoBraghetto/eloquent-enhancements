<?php

namespace Database\Models;

class Area extends AbstractModel
{
    protected $table = 'areas';

    protected $primaryKey = 'id_area';

    protected $fillable = [
        'name',
    ];

    protected $validation_rules = [
        'name' => 'required'
    ];

    protected $relationshipsModels = [
        'cities' => AreaCity::class,
    ];

    public function cities()
    {
        return $this->belongsToMany(City::class, 'areas_cities', 'id_area', 'id_city')->withPivot('id_area_city');
    }
}
