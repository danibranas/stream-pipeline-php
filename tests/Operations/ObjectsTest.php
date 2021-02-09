<?php

namespace Operations;

require_once 'User.php';

use Stream\Stream;
use Stream\Operations\Objects;
use PHPUnit\Framework\TestCase;

final class ObjectsTest extends TestCase
{
    public function testHasProperty()
    {
        $result = Stream::fromIterable($this->getUsers('John', 'Karl', 'Jennifer'))
            ->filter(Objects::hasProperty('surname'))
            ->toArray();

        $this->assertEmpty($result);

        $objects = [
            (object)[
                'name' => 'karl',
                'age' => 32,
            ],
            (object)[
                'name' => 'John',
                'surname' => 'Wick',
                'age' => 25,
            ],
            (object)[
                'name' => 'Jennifer',
                'age' => 36,
            ],
        ];

        $result = Stream::fromIterable($objects)
            ->filter(Objects::hasProperty('surname'))
            ->toArray();

        $this->assertEquals([
            (object)[
                'name' => 'John',
                'surname' => 'Wick',
                'age' => 25,
            ]
        ], $result);
    }

    public function testHasPropertyWithValue()
    {
        $result = Stream::fromIterable($this->getUsers('John', 'Karl', 'Jennifer'))
            ->filter(Objects::hasPropertyWithValue('surname', 'Wick'))
            ->toArray();

        $this->assertEmpty($result);

        $result = Stream::fromIterable($this->getUsers('John', 'Karl', 'Jennifer'))
            ->filter(Objects::hasPropertyWithValue('name', 'John'))
            ->toArray();

        $this->assertEquals([
            (object)[
                'name' => 'John',
                'age' => 30,
            ]
        ], $result);

        $result = Stream::fromIterable($this->getUsers('John', 'Karl', 'Jennifer'))
            ->filter(Objects::hasPropertyWithValue('age', 30))
            ->toArray();

        $this->assertEquals($this->getUsers('John', 'Karl', 'Jennifer'), $result);
    }

    public function testCallMethod()
    {
        $users = [
            new User('Ellen', 1),
            new User('Mike', 2),
            new User('Tom', 3),
        ];

        $result = Stream::fromIterable($users)
            ->filter(Objects::callMethod('isValid'))
            ->map(Objects::callMethod('getId'))
            ->toArray();

        $this->assertEquals([1, 3], $result);
    }

    public function testConstruct()
    {
        $result = Stream::of('A', 'B', 'C')
            ->map(Objects::construct(User::class))
            ->toArray();

        $this->assertEquals([new User('A'), new User('B'), new User('C')], $result);
    }

    public function testIsInstanceOf()
    {
        $result = Stream::of(1, 2, 'A', 'B', 'C')
            ->filter(function ($e) {
                return is_string($e);
            })
            ->map(Objects::construct(User::class))
            ->filter(Objects::isInstanceOf(User::class))
            ->toArray();

        $this->assertEquals([new User('A'), new User('B'), new User('C')], $result);
    }

    public function testCastObject()
    {
        $result = Stream::of(['a' => 12])
            ->findFirst();

        $this->assertEquals(['a' => 12], $result);

        $result = Stream::of(['a' => 12])
            ->map(Objects::castObject())
            ->findFirst();

        $this->assertEquals((object) ['a' => 12], $result);
    }

    public function testGetOperation()
    {
        $result = Stream::of(new User('David'))
            ->map(Objects::get('prop1'))
            ->findFirst();

        $this->assertEquals(10, $result);

        $result = Stream::of(new User('David'))
            ->map(Objects::get('prop2'))
            ->findFirst();

        $this->assertEquals(20, $result);

        $result = Stream::of(new User('David'))
            ->map(Objects::get('unexistent'))
            ->findFirst();

        $this->assertNull($result);
    }

    private function getUsers(string ...$names): array
    {
        return array_map(function ($name) {
            return (object)[
                'name' => $name,
                'age' => 30,
            ];
        }, $names);
    }
}
