<?php

use PHPUnit\Framework\TestCase;
use function Sigep\EloquentEnhancements\isAssoc;

class HelperIsAssocTest extends TestCase
{
    public function testShouldReturnFalseWhenArrayIsNotAssoiative()
    {
        $this->assertFalse(isAssoc([1, 2, 3]));
    }

    public function testShouldReturnTrueWhenArrayIsAssociative()
    {
        $this->assertTrue(isAssoc(['a' => 1, 'b' => 2, 'c' => 3]));
    }

    public function testShoudReturnFalseWhenArrayIsEmpty() {
        $this->assertFalse(isAssoc([]));
    }
}
