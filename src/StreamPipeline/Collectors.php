<?php


namespace StreamPipeline;


class Collectors
{
    public static function join(string $delimiter = ''): callable
    {
        return function ($accumulator, $item) use ($delimiter): string {
            return $accumulator . $delimiter . $item;
        };
    }
}