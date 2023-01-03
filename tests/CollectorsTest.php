<?php


use StreamPipeline\Operations\Logical;
use StreamPipeline\Operations\Numbers;
use StreamPipeline\Operations\Objects;
use StreamPipeline\Stream;
use PHPUnit\Framework\TestCase;
use StreamPipeline\Collectors;

final class CollectorsTest extends TestCase
{
    public function testJoinCollector()
    {
        $result = Stream::of('a', 'b', 'c', 'd', 'e', 'f')
            ->limit(5)
            ->collect(Collectors::join());
        $this->assertEquals('abcde', $result);

        $result = Stream::of('a', 'b', 'c', 'd', 'e', 'f')
            ->limit(5)
            ->collect(Collectors::join(','));
        $this->assertEquals('a,b,c,d,e', $result);

        $result = Stream::of()
            ->limit(5)
            ->collect(Collectors::join(','));
        $this->assertEquals('', $result);
    }

    public function testSumCollector()
    {
        $result = Stream::of(1, 2, 3, 4, 5, 6, 7, 8, 9)
            ->limit(5)
            ->collect(Collectors::sum());
        $this->assertEquals(15, $result);

        $result = Stream::of()
            ->limit(5)
            ->collect(Collectors::sum());
        $this->assertEquals(0, $result);
    }

    public function testGroupByCollector()
    {
        $data = [
            [
                'age' => 20,
                'name' => 'Johny',
            ],
            (object)[
                'age' => 20,
                'name' => 'Mario',
            ],
            [
                'age' => 30,
                'name' => 'Anna',
            ],
            [
                'age' => 30,
                'name' => 'Zeus',
            ],
            (object)[
                'age' => 20,
                'name' => 'Rachel',
            ],
            (object)[
                'age' => 40,
                'name' => 'Tomas',
            ],
        ];

        $result = Stream::fromIterable($data)
            ->collect(Collectors::groupBy(Objects::get('age')));

        $this->assertEquals([
            20 => [$data[0], $data[1], $data[4]],
            30 => [$data[2], $data[3]],
            40 => [$data[5]],
        ], $result);

        $result = Stream::of()
            ->limit(5)
            ->collect(Collectors::groupBy());
        $this->assertEquals([], $result);

        $result = Stream::fromIterable($data)
            ->collect(Collectors::groupBy(Objects::get('age'), Objects::get('name')));
        $this->assertEquals([
            20 => [$data[0]['name'], $data[1]->name, $data[4]->name],
            30 => [$data[2]['name'], $data[3]['name']],
            40 => [$data[5]->name],
        ], $result);
    }

    public function testGroupAndReduceByCollector()
    {
        $data = [
            [
                'age' => 20,
                'name' => 'Johny',
            ],
            (object)[
                'age' => 20,
                'name' => 'Mario',
            ],
            [
                'age' => 30,
                'name' => 'Anna',
            ],
            [
                'age' => 30,
                'name' => 'Zeus',
            ],
            (object)[
                'age' => 20,
                'name' => 'Rachel',
            ],
            (object)[
                'age' => 40,
                'name' => 'Tomas',
            ],
        ];

        $result = Stream::fromIterable($data)
            ->collect(Collectors::groupAndReduceBy(Objects::get('age')));

        $this->assertEquals([
            20 => $data[4],
            30 => $data[3],
            40 => $data[5],
        ], $result);

        $result = Stream::of()
            ->limit(5)
            ->collect(Collectors::groupAndReduceBy());
        $this->assertEquals([], $result);

        $result = Stream::fromIterable($data)
            ->collect(Collectors::groupAndReduceBy(Objects::get('age'), Objects::get('name'),
                Logical::identity()));
        $this->assertEquals([
            20 => $data[0]['name'],
            30 => $data[2]['name'],
            40 => $data[5]->name,
        ], $result);

        $result = Stream::of(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
            ->filter(Numbers::isEven())
            ->collect(Collectors::groupAndReduceBy(null, Logical::true()));

        $this->assertEquals([2 => true, 4 => true, 6 => true, 8 => true, 10 => true], $result);

        $result = Stream::of(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
            ->filter(Numbers::isEven())
            ->collect(Collectors::groupAndReduceBy(function ($n) {
                return $n + 1;
            }, Logical::true()));

        $this->assertEquals([3 => true, 5 => true, 7 => true, 9 => true, 11 => true], $result);

        $result = Stream::of(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
            ->filter(Numbers::isEven())
            ->collect(Collectors::groupAndReduceBy(function ($n) {
                return $n + 1;
            }, function ($n) {
                return $n + 2;
            }));

        $this->assertEquals([3 => 4, 5 => 6, 7 => 8, 9 => 10, 11 => 12], $result);
    }
}