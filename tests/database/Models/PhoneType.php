<?php

namespace Database\Models;

class PhoneType extends AbstractModel
{
    protected $table = 'phones_types';

    protected $primaryKey = 'id_phone_type';

    protected $fillable = [
        'name',
    ];

    protected $validation_rules = [
        'name' => 'required',
    ];

    public function phones()
    {
        return $this->hasMany(Phone::class, 'id_phone_type');
    }
}
