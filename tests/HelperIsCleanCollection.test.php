<?php

use PHPUnit\Framework\TestCase;
use function Sigep\EloquentEnhancements\cleanCollection;

class HelperIsCleanCollection extends TestCase
{
    public function testShouldReturnEmptyArrayWhenCollectionIsEmpty()
    {
        $this->assertEquals([], cleanCollection([]));
    }

    public function testShouldRemoveEmptyArrayFromTheCollection()
    {
        $input = [
            ['name' => 'a'],
            ['name' => 'b'],
            [],
            ['name' => 'c'],
            [],
        ];

        $expected = [
            ['name' => 'a'],
            ['name' => 'b'],
            ['name' => 'c'],
        ];

        $real = cleanCollection($input);

        $this->assertEquals($expected, $real);
    }

    public function testShouldThrowExceptionWhenCollectionIsAssociative()
    {
        $this->expectException(InvalidArgumentException::class);
        cleanCollection(['a' => 1, 'b' => 2, 'c' => 3]);
    }
}
