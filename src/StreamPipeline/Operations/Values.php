<?php


namespace StreamPipeline\Operations;


final class Values
{
    public static function isEmpty(): callable
    {
        return function ($element): bool {
            return empty($element);
        };
    }

    public static function isNotEmpty(): callable
    {
        return function ($element): bool {
            return !empty($element);
        };
    }

    public static function isNull(): callable
    {
        return function ($element): bool {
            return is_null($element);
        };
    }

    public static function isNotNull(): callable
    {
        return function ($element): bool {
            return !is_null($element);
        };
    }

    public static function equalsTo($value, bool $strict = true): callable
    {
        return function ($element) use ($value, $strict): bool {
            return $strict
                ? $element === $value
                : $element == $value;
        };
    }

    public static function filterVar(string $filter, $options = null): callable
    {
        return function ($element) use ($filter, $options) {
            return filter_var($element, $filter, $options ?? 0);
        };
    }
}
