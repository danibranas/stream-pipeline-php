<?php

namespace Operations;

use Stream\Stream;
use Stream\Operations\Strings;
use PHPUnit\Framework\TestCase;

final class StringsTest extends TestCase
{
    public function testToStringOperation()
    {
        $result = Stream::of(1, 1.0, '1', 2, '2', null)
            ->map(Strings::toString())
            ->toArray();

        $this->assertEquals(['1', '1', '1', '2', '2', ''], $result);
    }

    public function testTrimOperation()
    {
        $result = Stream::of('   hello world! ', ' hi ')
            ->map(Strings::trim())
            ->toArray();

        $this->assertEquals(['hello world!', 'hi'], $result);
    }

    public function testToLowerOperation()
    {
        $result = Stream::of('GOOD', 'Morning', 'sTars')
            ->map(Strings::toLower())
            ->toArray();

        $this->assertEquals(['good', 'morning', 'stars'], $result);
    }

    public function testToUpperOperation()
    {
        $result = Stream::of('good', 'Morning', 'sTars')
            ->map(Strings::toUpper())
            ->toArray();

        $this->assertEquals(['GOOD', 'MORNING', 'STARS'], $result);
    }

    public function testUcwordsOperation()
    {
        $result = Stream::of('GOOD', 'morning', 'stars')
            ->map(Strings::ucwords())
            ->toArray();

        $this->assertEquals(['GOOD', 'Morning', 'Stars'], $result);
    }

    public function testConcatOperation()
    {
        $result = Stream::of('good', 'morning', 'stars')
            ->map(Strings::concat(' <3'))
            ->toArray();

        $this->assertEquals(['good <3', 'morning <3', 'stars <3'], $result);
    }

    public function testSubstrOperation()
    {
        $result = Stream::of('good', 'morning', 'stars')
            ->map(Strings::substr(0, 3))
            ->toArray();

        $this->assertEquals(['goo', 'mor', 'sta'], $result);
    }

    public function testStartsWithOperation()
    {
        $result = Stream::of('good', 'morning', 'stars', 'Move')
            ->filter(Strings::startsWith('mo'))
            ->toArray();

        $this->assertEquals(['morning'], $result);
    }

    public function testIsStringOperation()
    {
        $result = Stream::of('good', 12, 'morning', null, [], 'stars', 'Move')
            ->filter(Strings::isString())
            ->toArray();

        $this->assertEquals(['good', 'morning', 'stars', 'Move'], $result);
    }
}