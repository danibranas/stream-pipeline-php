<?php

use StreamPipeline\Collectors;
use StreamPipeline\Stream;
use StreamPipeline\Iterators\NumberGenerator;
use StreamPipeline\Operations\Numbers;
use StreamPipeline\Operations\Strings;
use StreamPipeline\Operations\Values;
use PHPUnit\Framework\TestCase;

final class StreamTest extends TestCase
{
    public function testExample1Works()
    {
        $result = Stream::of(" tom ", null, " jerry ", null, null)
            ->filter(Values::isNotNull())
            ->map(Strings::trim())
            ->map(Strings::ucwords())
            ->limit(10);

        $this->assertEquals(['Tom', 'Jerry'], $result->toArray());
    }

    public function testExample2Works()
    {
        $result = Stream::of(' B1 ', ' B2', 'a1 ', ' a2 ', 'a3', ' b1', ' b2', 'b3')
            ->map(Strings::trim())
            ->map(Strings::toUpper())
            ->filter(Strings::startsWith('B'))
            ->distinct()
            ->collect(Collectors::join(','));

        $this->assertEquals('B1,B2,B3', $result);
    }

    public function testToArrayOperation()
    {
        $result = Stream::of(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
            ->filter(Numbers::isEven())
            ->toArray();

        $this->assertEquals([2, 4, 6, 8, 10], $result);
    }

    public function testInfiniteIterate()
    {
        $result = Stream::iterate(1, new NumberGenerator(1))
            ->filter(Numbers::isEven())
            ->skip(5)
            ->limit(11);

        $this->assertEquals([12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32], $result->toArray());
    }

    public function testMapOperation(): void
    {
        $pipeline = Stream::of(10, 23, 35, 41, 56, 66, 70);
        $this->assertEquals([10, 23, 35, 41, 56, 66, 70], $pipeline->toArray());


        $pipeline = Stream::of(10, 23, 35, 41, 56, 66, 70)
            ->map(function ($e) {
                return $e * 10;
            });
        $this->assertEquals([100, 230, 350, 410, 560, 660, 700], $pipeline->toArray());

        $pipeline = Stream::of(10, 23, 35, 41, 56, 66, 70)
            ->map(function ($e) {
                return $e * 10;
            })
            ->map(function ($e) {
                return $e * 2;
            })
            ->map(function ($e) {
                return $e + 1;
            });
        $this->assertEquals([201, 461, 701, 821, 1121, 1321, 1401], $pipeline->toArray());

        $pipeline = Stream::of(10, 23, 35, 41, 56, 66, 70)
            ->map(function ($ignored, $i) {
                return $i;
            })
            ->map(function ($e, $i) {
                return $e + $i;
            });

        $this->assertEquals([0, 2, 4, 6, 8, 10, 12], $pipeline->toArray());
    }

    public function testFilterOperation()
    {
        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->toArray();
        $this->assertEquals(["a1", "a2", "b1", "b2", "b3", "c1", "c3"], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->filter(function ($e) {
                return substr($e, 0, 1) === 'c';
            })
            ->toArray();
        $this->assertEquals(["c1", "c3"], $result);
    }

    public function testPeekOperation()
    {
        $expectedPeek1 = ["a1", "a2", "b1", "b2", "b3", "c1", "c3"];
        $expectedPeek2 = ["b1", "b2", "b3"];

        Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->peek(function ($e, $i) use ($expectedPeek1) {
                $this->assertEquals($expectedPeek1[$i], $e);
            })
            ->filter(function ($e) {
                return substr($e, 0, 1) === 'b';
            })
            ->peek(function ($e, $i) use ($expectedPeek2) {
                $this->assertEquals($expectedPeek2[$i], $e);
            })
            ->toArray();
    }

    public function testLimitOperation()
    {
        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->limit(5)
            ->toArray();
        $this->assertEquals(["a1", "a2", "b1", "b2", "b3"], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->limit(1)
            ->toArray();
        $this->assertEquals(["a1"], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->limit(0)
            ->toArray();
        $this->assertEquals([], $result);


        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->limit(-1)
            ->toArray();
        $this->assertEquals([], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->limit(80)
            ->toArray();
        $this->assertEquals(["a1", "a2", "b1", "b2", "b3", "c1", "c3"], $result);
    }

    public function testSkipOperation()
    {
        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->skip(5)
            ->toArray();
        $this->assertEquals(["c1", "c3"], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->skip(1)
            ->toArray();
        $this->assertEquals(["a2", "b1", "b2", "b3", "c1", "c3"], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->skip(0)
            ->toArray();
        $this->assertEquals(["a1", "a2", "b1", "b2", "b3", "c1", "c3"], $result);


        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->skip(-1)
            ->toArray();
        $this->assertEquals(["a1", "a2", "b1", "b2", "b3", "c1", "c3"], $result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->skip(80)
            ->toArray();
        $this->assertEquals([], $result);
    }

    public function testDistinctOperation()
    {
        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->distinct()
            ->toArray();
        $this->assertEquals(["a1", "b2", "b3", "A1", "c1"], $result);

        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->distinct(function ($e) {
                return strtoupper($e);
            })
            ->toArray();
        $this->assertEquals(["a1", "b2", "b3", "c1"], $result);
    }

    public function testFindFirstOperation()
    {
        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->findFirst();
        $this->assertEquals("a1", $result);

        $result = Stream::of()
            ->findFirst();
        $this->assertNull($result);

        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->skip(4)
            ->findFirst();
        $this->assertEquals("b3", $result);

        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->skip(50)
            ->findFirst();
        $this->assertEquals(null, $result);
    }

    public function testCountOperation()
    {
        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->count();
        $this->assertEquals(7, $result);

        $result = Stream::of()
            ->count();
        $this->assertEquals(0, $result);

        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->skip(4)
            ->count();
        $this->assertEquals(3, $result);

        $result = Stream::of("a1", "a1", "b2", "a1", "b3", "A1", "c1")
            ->skip(50)
            ->count();
        $this->assertEquals(0, $result);
    }

    public function testForEachOperation()
    {
        $expectedForEach = ["b1", "b2", "b3"];

        Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->filter(function ($e) {
                return substr($e, 0, 1) === 'b';
            })
            ->forEach(function ($e, $i) use ($expectedForEach) {
                $this->assertEquals($expectedForEach[$i], $e);
            });
    }

    public function testAnyMatchOperation()
    {
        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->anyMatch(function ($e) {
                return $e === 'b2';
            });
        $this->assertTrue($result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->anyMatch(function ($e) {
                return $e === 'D2';
            });
        $this->assertFalse($result);

        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->anyMatch(function () {
                return false;
            });
        $this->assertFalse($result);
    }

    public function testAllMatchOperation()
    {
        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->allMatch(function ($e) {
                return strlen($e) === 2;
            });
        $this->assertTrue($result);

        $result = Stream::of("a1", "a2", "a1", "a2", "a3", "a1", "a3")
            ->allMatch(function ($e) {
                return substr($e, 0, 1) === 'a';
            });
        $this->assertTrue($result);

        $result = Stream::of("a1", "a2", "a1", "a2", "b3", "a1", "a3")
            ->allMatch(function ($e) {
                return substr($e, 0, 1) === 'a';
            });
        $this->assertFalse($result);
    }

    public function testNoneMatchOperation()
    {
        $result = Stream::of("a1", "a2", "b1", "b2", "b3", "c1", "c3")
            ->noneMatch(function ($e) {
                return strlen($e) === 2;
            });
        $this->assertFalse($result);

        $result = Stream::of("a1", "a2", "a1", "a2", "a3", "a1", "a3")
            ->noneMatch(function ($e) {
                return substr($e, 0, 1) === 'a';
            });
        $this->assertFalse($result);

        $result = Stream::of("a1", "a2", "a1", "a2", "b3", "a1", "a3")
            ->noneMatch(function ($e) {
                return substr($e, 0, 1) === 'c';
            });
        $this->assertTrue($result);
    }

    public function testReduceOperation()
    {
        $result = Stream::of(1, 2, 3, 4, 5, 6, 7, 8)
            ->skip(1)
            ->reduce(function ($accum, $item) {
                return $accum + $item;
            }, 4);
        $this->assertEquals(39, $result);

        $result = Stream::of()
            ->skip(1)
            ->reduce(function ($accum, $item) {
                return $accum + $item;
            }, 4);
        $this->assertEquals(4, $result);
    }

    public function testCollectOperation()
    {
        $result = Stream::of("hi,", "hello,", "how", "are", "you?")
            ->skip(1)
            ->collect(function ($accum, $item) {
                return $accum . ' ' . $item;
            });
        $this->assertEquals('hello, how are you?', $result);

        $result = Stream::of()
            ->skip(1)
            ->collect(function ($accum, $item) {
                return $accum . ' ' . $item;
            });
        $this->assertEquals(null, $result);
    }
}
