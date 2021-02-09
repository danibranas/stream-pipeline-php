<?php


use Stream\Stream;
use PHPUnit\Framework\TestCase;
use Stream\Collectors;

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
        $this->assertEquals(null, $result);
    }
}