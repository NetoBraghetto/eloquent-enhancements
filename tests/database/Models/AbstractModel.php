<?php

namespace Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator as Validator;
use Sigep;

abstract class AbstractModel extends Model
{
    use Sigep\EloquentEnhancements\Traits\Error;
    use Sigep\EloquentEnhancements\Traits\SaveAll;

    public function save(array $options = [])
    {
        $data = ($options) ? $options : $this->getAttributes();
        $validator = Validator::make($data, $this->validation_rules);
        if ($validator->fails()) {
            $this->setErrors($validator->errors());
            return false;
        }

        return parent::save($data);
    }
}
