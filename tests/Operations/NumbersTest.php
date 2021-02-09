<?php


namespace Operations;


use Stream\Stream;
use Stream\Operations\Numbers;
use PHPUnit\Framework\TestCase;

final class NumbersTest extends TestCase
{
    public function testIsOddOperation()
    {
        $result = Stream::of()
            ->filter(Numbers::isOdd());

        $this->assertEquals([], $result->toArray());

        $result = Stream::of(1, 2, 3, 4, 5, 6)
            ->filter(Numbers::isOdd());

        $this->assertEquals([1, 3, 5], $result->toArray());
    }

    public function testIsEvenOperation()
    {
        $result = Stream::of()
            ->filter(Numbers::isEven());

        $this->assertEquals([], $result->toArray());


        $result = Stream::of(1, 2, 3, 4, 5, 6)
            ->filter(Numbers::isEven());

        $this->assertEquals([2, 4, 6], $result->toArray());
    }

    public function testPlusOperation()
    {
        $result = Stream::of()
            ->map(Numbers::plus(10));

        $this->assertEquals([], $result->toArray());


        $result = Stream::of(1, 2, 3, 4, 5, 6)
            ->map(Numbers::plus(10));

        $this->assertEquals([11, 12, 13, 14, 15, 16], $result->toArray());
    }

    public function testMultiplyOperation()
    {
        $result = Stream::of()
            ->map(Numbers::multiply(10));

        $this->assertEquals([], $result->toArray());


        $result = Stream::of(1, 2, 3, 4, 5, 6)
            ->map(Numbers::multiply(10));

        $this->assertEquals([10, 20, 30, 40, 50, 60], $result->toArray());
    }

    public function testToIntOperation()
    {
        $result = Stream::of('1', 2, 3.0, '4')
            ->map(Numbers::toInt())
            ->toArray();

        $this->assertCount(4, $result);

        foreach ($result as $num) {
            $this->assertIsInt($num);
        }
    }

    public function testToFloatOperation()
    {
        $result = Stream::of('1', 2, 3.0, '4')
            ->map(Numbers::toFloat())
            ->toArray();

        $this->assertCount(4, $result);

        foreach ($result as $num) {
            $this->assertIsFloat($num);
        }
    }

    public function testIsNumeric()
    {
        $result = Stream::of('1', 2, 3.0, '4', 'abc')
            ->filter(Numbers::isNumeric())
            ->toArray();

        $this->assertEquals(['1', 2, 3.0, '4'], $result);
    }

    public function testIsGreaterThan()
    {
        $result = Stream::of(1, 2, 3, 4)
            ->filter(Numbers::isGreaterThan(2))
            ->toArray();

        $this->assertEquals([3, 4], $result);
    }

    public function testIsLessThan()
    {
        $result = Stream::of(1, 2, 3, 4)
            ->filter(Numbers::isLessThan(3))
            ->toArray();

        $this->assertEquals([1, 2], $result);
    }

    public function testIsLessOrEqualThan()
    {
        $result = Stream::of(1, 2, 3, 4)
            ->filter(Numbers::isLessOrEqualThan(3))
            ->toArray();

        $this->assertEquals([1, 2, 3], $result);
    }

    public function testIsGreaterOrEqualThan()
    {
        $result = Stream::of(1, 2, 3, 4)
            ->filter(Numbers::isGreaterOrEqualThan(2))
            ->toArray();

        $this->assertEquals([2, 3, 4], $result);
    }
}