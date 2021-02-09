<?php


namespace Operations;


use Stream\Stream;
use Stream\Operations\Logical;
use PHPUnit\Framework\TestCase;

final class LogicalTest extends TestCase
{
    public function testIdentityOperation()
    {
        $result = Stream::of(1, true, 0, 'ok', false)
            ->filter(Logical::identity())
            ->toArray();

        $this->assertEquals([1, true, 'ok'], $result);
    }

    public function testNotOperation()
    {
        $result = Stream::of(1, true, 0, 'ok', false)
            ->filter(Logical::not(function ($e) {
                return (boolean) $e;
            }))
            ->toArray();

        $this->assertEquals([0, false], $result);
    }
}