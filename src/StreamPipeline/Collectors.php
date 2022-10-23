<?php


namespace StreamPipeline;


use StreamPipeline\Operations\Logical;

final class Collectors
{
    public static function join(string $delimiter = ''): callable
    {
        return function ($accumulator, $item, int $index) use ($delimiter): string {
            if ($index < 1) {
                return $item ?? '';
            }

            return $accumulator . $delimiter . $item;
        };
    }

    public static function sum(?callable $mapper = null): callable
    {
        return function ($accumulator, $item, int $index) use ($mapper) {
            if ($index < 1) {
                return $item ?? 0;
            }

            return $accumulator + (!is_null($mapper) ? $mapper($item) : $item);
        };
    }

    public static function groupBy(?callable $classifier = null): callable
    {
        $classifierFunction = !is_null($classifier)
            ? $classifier
            : Logical::identity();

        return function ($accumulator, $item, int $index) use ($classifierFunction) {
            if ($index < 1) {
                $accumulator = [];
            }

            if (is_null($item)) {
                return $accumulator;
            }

            $accumulatorIndex = $classifierFunction($item);

            if (!isset($accumulator[$accumulatorIndex])) {
                $accumulator[$accumulatorIndex] = [];
            }

            $accumulator[$accumulatorIndex][] = $item;

            return $accumulator;
        };
    }
}
