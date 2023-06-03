<?php

namespace Database\Models;

class User extends AbstractModel
{
    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'email',
    ];

    protected $validation_rules = [
        'name' => 'required',
        'email' => 'email',
    ];

    protected $relationshipsLimits = [
        'phones' => '1:2',
    ];

    public function phones()
    {
        return $this->hasMany(Phone::class, 'id_user');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'id_user');
    }
}
