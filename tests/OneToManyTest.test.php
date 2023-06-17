<?php

use Database\Models\Car;
use Database\Models\City;
use Database\Models\PhoneType;
use Database\Models\User;
use Illuminate\Support\MessageBag;

class OneToManyTest extends AbstractTestCase
{
    public function testCreateWithRelationships()
    {
        $input = [
            'name' => 'Luís Henrique Faria',
            'email' => 'luish.faria@gmail.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1]
            ]
        ];

        $this->assertTrue(with(new User)->createAll($input));
        $luis = User::whereEmail('luish.faria@gmail.com')->with('phones')->first();
        $this->assertEquals(2, count($luis->phones));

        $luisAsArray = collect($luis->toArray());
        $output = [
            'name' => $luisAsArray->get('name'),
            'email' => $luisAsArray->get('email'),
            'phones' => collect($luisAsArray->get('phones'))->map(function ($phone) {
                return [
                    'number' => $phone['number'],
                    'label' => $phone['label'],
                    'id_phone_type' => $phone['id_phone_type'],
                ];
            })->toArray(),
        ];
        $this->assertEquals($input, $output);
    }

    public function testEditRelationships()
    {
        $input = [
            'name' => 'Luís Henrique Faria',
            'email' => 'luish.faria@gmail.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1]
            ]
        ];

        $this->assertTrue(with(new User)->createAll($input));
        $luis = User::whereEmail('luish.faria@gmail.com')->with('phones')->first();

        $input['name'] = 'Luís';
        $input['email'] = 'luque@luque.cc';
        $input['phones'][0]['id_phone'] = $luis->phones[0]->id_phone;
        $input['phones'][0]['number'] = '000000';

        $input['phones'][1]['id_phone'] = $luis->phones[1]->id_phone;
        $input['phones'][1]['number'] = '111111';
        $input['phones'][1]['id_phone_type'] = 2;

        $this->assertTrue($luis->saveAll($input));

        $updatedLuis = User::whereEmail('luque@luque.cc')->with('phones')->first();
        $this->assertEquals($luis->id_user, $updatedLuis->id_user);
        $this->assertEquals(2, count($updatedLuis->phones));
        $this->assertEquals($input['name'], $updatedLuis->name);
        $this->assertEquals($input['phones'][0]['number'], $updatedLuis->phones[0]->number);
        $this->assertEquals($input['phones'][0]['id_phone_type'], $updatedLuis->phones[0]->id_phone_type);
        $this->assertEquals($input['phones'][1]['number'], $updatedLuis->phones[1]->number);
        $this->assertEquals($input['phones'][1]['id_phone_type'], $updatedLuis->phones[1]->id_phone_type);
    }

    public function testIgnoringEmptyData()
    {
        $input = [
            'name' => 'Luís Henrique Faria',
            'email' => 'luish.faria@msn.com',
            'phones' => [
                ['number' => '', 'label' => '', 'id_phone_type' => ''],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1],
            ]
        ];

        $this->assertTrue(with(new User)->createAll($input));
        $object = User::whereEmail('luish.faria@msn.com')->with('phones')->first();
        $this->assertEquals(1, count($object->phones));
    }

    public function testTransportingErrors()
    {
        $input = [
            'name' => 'Luís Henrique Faria',
            'email' => 'luish.faria@gmail.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1],
            ]
        ];

        $object = new User;
        $testInput = $input;
        unset($testInput['name']);
        $this->assertFalse($object->createAll($testInput));
        $this->assertTrue($object->errors()->has('name'));

        $object = new User;
        $testInput = $input;
        $testInput['email'] = 'xxx@gmail.com';
        unset($testInput['phones'][1]['number']);
        DB::beginTransaction();
        $this->assertFalse($object->createAll($testInput));
        $this->assertEquals(1, count($object->errors()->get('phones.1.number')));
        DB::rollback();

        $this->assertEquals(0, User::whereEmail('xxx@gmail.com')->count());
    }

    public function testShouldApplyLimitOnRelationship()
    {
        $input = [
            'name' => 'User User',
            'email' => 'user@domain.tld',
            'phones' => [
                ['number' => '111111', 'label' => 'phone a', 'id_phone_type' => 1],
                ['number' => '222222', 'label' => 'phone b', 'id_phone_type' => 1],
                ['number' => '333333', 'label' => 'phone c', 'id_phone_type' => 1],
                ['number' => '444444', 'label' => 'phone d', 'id_phone_type' => 1],
            ]
        ];

        $user = new User;
        DB::beginTransaction();
        $this->assertFalse($user->createAll($input));
        $this->assertTrue($user->errors()->has('phones'));
        DB::rollback();

        $input = [
            'name' => 'User',
            'email' => 'user@domain.otld',
            'phones' => [],
        ];
        $user = new User;
        DB::beginTransaction();
        $this->assertFalse($user->createAll($input));
        $this->assertTrue($user->errors()->has('phones'));
        DB::rollback();

        $input = [
            'name' => 'User',
            'email' => 'user@domain.otld',
        ];
        $user = new User;
        DB::beginTransaction();
        $this->assertFalse($user->createAll($input));
        $this->assertTrue($user->errors()->has('phones'));
        DB::rollback();

        $input = [
            'name' => 'User User',
            'email' => 'user@domain.tld',
            'phones' => [
                ['number' => '111111', 'label' => 'phone a', 'id_phone_type' => 1],
                ['number' => '222222', 'label' => 'phone b', 'id_phone_type' => 1],
            ]
        ];
        $user = new User;
        DB::beginTransaction();
        $this->assertTrue($user->createAll($input));
        $user->load('phones');
        $this->assertTrue($user->saveAll($user->toArray()));
        DB::commit();

    }

    public function testShouldSaveRelationshipsWithNonSequentialArrayKeys()
    {
        $input = [
            'name' => 'User User',
            'email' => 'user@domain.tld',
            'phones' => [
                1 => ['number' => '111111', 'label' => 'phone a', 'id_phone_type' => 1],
                3 => ['number' => '222222', 'label' => 'phone b', 'id_phone_type' => 1],
            ]
        ];

        $user = new User;
        $this->assertTrue($user->createAll($input));
        $user = User::whereEmail('user@domain.tld')->with('phones')->first();
        $this->assertEquals(2, count($user->phones));
    }

    public function testShouldCreateParent()
    {
        $input = [
            'name' => 'Steve',
            'email' => 'steve@monster.com',
            'phones' => array(
                array(
                    'number' => '1111111111',
                    'label' => 'phone x',
                    'type' => array(
                        'name' => 'phone type x',
                    )
                )
            ),
        ];

        $user = new User;
        $this->assertTrue($user->createAll($input));

        $phoneType = PhoneType::whereName($input['phones']['0']['type']['name'])->count();
        $this->assertEquals(1, $phoneType);
    }

    public function testShouldReturnFalseIfFailsToCreateParent()
    {
        $input = [
            'name' => 'Steve',
            'email' => 'steve@monster.com',
            'phones' => array(
                array(
                    'number' => '1111111111',
                    'label' => 'phone x',
                    'type' => array(
                        'foo' => 'bar'
                    )
                )
            ),
        ];

        $user = new User;
        $this->assertFalse($user->createAll($input));
        $this->assertTrue($user->errors()->has('phones.0.type.name'));
    }

    public function testShouldReturnFalseIfFailsToCreateParentOnUpdate()
    {
        $id = uniqid();
        $input = [
            'name' => $id,
            'email' => "$id@monster.com",
            'phones' => array(
                array(
                    'number' => '1111111111',
                    'label' => 'phone x',
                    'type' => array(
                        'name' => $id,
                    )
                )
            ),
        ];

        $user = new User;
        $this->assertTrue($user->createAll($input));

        $brokenInput = $input;
        $brokenInput['phones'][0]['type'] = ['foo' => 'bar'];
        $this->assertFalse($user->saveAll($brokenInput));
        $this->assertTrue($user->errors()->has('phones.0.type.name'));
    }

    public function testShouldNotCreateIfFailsToCreateBelongsTo()
    {
        $input = [
            'name' => 'Steve',
            'email' => 'steve@monster.com',
            'phones' => array(
                array(
                    'number' => '1111111111',
                    'label' => 'phone x',
                    'type' => array(
                        'name' => '',
                    )
                )
            ),
        ];

        $user = new User;
        $this->assertFalse($user->createAll($input));
        $this->assertTrue($user->errors()->has('phones.0.type.name'));
    }

    public function testFillableOption()
    {
        $input = [
            'name' => 'Geremias',
            'email' => 'GEREMIAS@GMAIL.com',
            'phones' => [
                ['number' => '1111111111', 'label' => 'cel', 'id_phone_type' => 1],
                ['number' => '111114441', 'label' => 'cel 2', 'id_phone_type' => 1]
            ],
        ];

        $user = new User();
        $save = $user->createAll($input, [
            User::class => [
                'fillable' => ['name']
            ],
        ]);

        $this->assertTrue($save);
        $this->assertEquals($input['name'], $user->name);
        $this->assertEquals(null, $user->email);
    }

    public function testShouldReturnFalseWhenUpdatdingWithInvalidData()
    {
        $city = new City();
        $this->assertTrue($city->createAll(['name' => uniqid()]));
        $this->assertFalse($city->saveAll(['name' => '']));
    }
}
