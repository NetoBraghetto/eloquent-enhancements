<?php

use Database\Models\User;
use Illuminate\Support\MessageBag;

class ErrorsTest extends AbstractTestCase
{
    public function testShouldReturnEmptyMessageBagWhenNoErrors()
    {
        $userModel = new User;
        $this->assertTrue($userModel->errors() instanceof Illuminate\Support\MessageBag);
        $this->assertTrue($userModel->errors()->isEmpty());
    }

    public function testShouldSetErrors()
    {
        $userModel = new User;
        $errors = new MessageBag([]);
        $errors->add('name', 'name is required');
        $userModel->setErrors($errors);

        $this->assertEquals(['name' => ['name is required']], $userModel->errors()->toArray());
    }

    public function testShouldAddError()
    {
        $userModel = new User;
        $userModel->addError('name', 'name is required');

        $this->assertEquals(['name' => ['name is required']], $userModel->errors()->toArray());
    }
}
