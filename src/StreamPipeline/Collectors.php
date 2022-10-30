<?php


namespace StreamPipeline;


use StreamPipeline\Operations\Logical;

/**
 * Various useful reduction operations to use with collect terminal operator within a Stream flow.
 */
final class Collectors
{
    /**
     * Reduces the Stream by joining the elements. If a $delimiter is set, it will be used as joining string.
     * @param string $delimiter the joining string.
     * @return callable a callable function.
     */
    public static function join(string $delimiter = ''): callable
    {
        return function ($accumulator, $item, int $index) use ($delimiter): string {
            if ($index < 1) {
                return $item ?? '';
            }

            return $accumulator . $delimiter . $item;
        };
    }

    /**
     * Reduces the Stream by summing the elements. If a $mapper is set, it will be used to map elements before
     * reduction.
     * @param callable|null $mapper an optional mapper.
     * @return callable a callable function.
     */
    public static function sum(?callable $mapper = null): callable
    {
        return function ($accumulator, $item, int $index) use ($mapper) {
            if ($index < 1) {
                return $item ?? 0;
            }

            return $accumulator + (!is_null($mapper) ? $mapper($item) : $item);
        };
    }

    /**
     * Reduces the Stream by grouping the elements into an associative array of arrays.
     * @param callable|null $classifier an optional classifier function that can be passed to get the classifier index.
     * @param callable|null $mapper an optional mapper function that can be passed to map que resulting elements.
     * @return callable a callable function.
     */
    public static function groupBy(?callable $classifier = null, ?callable $mapper = null): callable
    {
        $classifierFunction = !is_null($classifier)
            ? $classifier
            : Logical::identity();

        $mapperFunction = !is_null($mapper)
            ? $mapper
            : Logical::identity();

        return function ($accumulator, $item, int $index) use ($classifierFunction, $mapperFunction) {
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

            $accumulator[$accumulatorIndex][] = $mapperFunction($item);

            return $accumulator;
        };
    }
}
