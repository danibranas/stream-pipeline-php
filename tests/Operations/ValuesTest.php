<?php


namespace Operations;


use Stream\Stream;
use Stream\Operations\Values;
use PHPUnit\Framework\TestCase;

final class ValuesTest extends TestCase
{
    public function testIsEmpty()
    {
        $result = Stream::of(1, null, 2, 'abcd', '', [], 0)
            ->filter(Values::isEmpty())
            ->toArray();

        $this->assertEquals([null, '', [], 0], $result);
    }

    public function testIsNotEmpty()
    {
        $result = Stream::of(1, null, 2, 'abcd', '', [], 0)
            ->filter(Values::isNotEmpty())
            ->toArray();

        $this->assertEquals([1, 2, 'abcd'], $result);
    }

    public function testIsNull()
    {
        $result = Stream::of(1, null, 2, 'abcd', '', [], 0)
            ->filter(Values::isNull())
            ->toArray();

        $this->assertEquals([null], $result);
    }

    public function testIsNotNull()
    {
        $result = Stream::of(1, null, 2, 'abcd', '', [], 0)
            ->filter(Values::isNotNull())
            ->toArray();

        $this->assertEquals([1, 2, 'abcd', '', [], 0], $result);
    }

    public function testEqualsTo()
    {
        $result = Stream::of(1, '1', 2, '2', null, [])
            ->filter(Values::equalsTo(1))
            ->toArray();

        $this->assertEquals([1], $result);

        $result = Stream::of(1, '1', 2, '2', null, [])
            ->filter(Values::equalsTo(1, false))
            ->toArray();

        $this->assertEquals([1, '1'], $result);
    }

    public function testFilterVar()
    {
        $result = Stream::of('john(.doe)@exa//mple.com')
            ->map(Values::filterVar(FILTER_SANITIZE_EMAIL))
            ->findFirst();
        $this->assertEquals('john.doe@example.com', $result);
    }
}