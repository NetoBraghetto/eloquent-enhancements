<?php

use Database\Models\Car;
use Database\Models\User;
use Illuminate\Support\MessageBag;

class ValidationTest extends AbstractTestCase {
    public function testShouldUseProvidedValidatorCallback()
    {
        $input = [
            'name' => 'Geremias',
            'email' => 'GEREMIAS@GMAIL.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1]
            ],
            'cars' => [
                ['vendor' => 'Peugeot', 'model' => '207'],
                ['vendor' => 'Volvo']
            ]
        ];

        $user = new User();
        $save = $user->createAll($input, [
            Car::class => [
                'validator' => function ($model) {
                    $errors = new MessageBag();
                    if (!$model->vendor) {
                        $errors->add('vendor', 'required');
                    }
                    if (!$model->model) {
                        $errors->add('model', 'required');
                    }

                    if ($errors->count()) {
                        return $errors;
                    } else {
                        return true;
                    }
                }
            ],
        ]);

        $this->assertFalse($save);
        $this->assertTrue($user->errors()->has('cars.1.model'));
    }

    public function testShouldUseProvidedGlobalValidatorCallback()
    {
        $input = [
            'name' => 'Geremias',
            'email' => 'GEREMIAS@GMAIL.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1]
            ],
            'cars' => [
                ['vendor' => 'Peugeot', 'model' => '207'],
                ['vendor' => 'Volvo']
            ]
        ];

        $user = new User();
        $save = $user->createAll($input, [
            'validator' => function ($model) {
                if ($model instanceof Car === false) {
                    return true;
                }

                $errors = new MessageBag();
                if (!$model->vendor) {
                    $errors->add('vendor', 'required');
                }
                if (!$model->model) {
                    $errors->add('model', 'required');
                }

                if ($errors->count()) {
                    return $errors;
                } else {
                    return true;
                }
            }
        ]);

        $this->assertFalse($save);
        $this->assertTrue($user->errors()->has('cars.1.model'));
    }

    public function testShouldUserProvidedValidatorCallbackUsingClassShortName()
    {
        $input = [
            'name' => 'Geremias',
            'email' => 'GEREMIAS@GMAIL.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1]
            ],
            'cars' => [
                ['vendor' => 'Peugeot', 'model' => '207'],
                ['vendor' => 'Volvo']
            ]
        ];

        $user = new User();
        $save = $user->createAll($input, [
            'Car' => [
                'validator' => function ($model) {
                    $errors = new MessageBag();
                    if (!$model->vendor) {
                        $errors->add('vendor', 'required');
                    }
                    if (!$model->model) {
                        $errors->add('model', 'required');
                    }

                    if ($errors->count()) {
                        return $errors;
                    } else {
                        return true;
                    }
                }
            ],
        ]);

        $this->assertFalse($save);
        $this->assertTrue($user->errors()->has('cars.1.model'));
    }
}
