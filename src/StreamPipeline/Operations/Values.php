<?php


namespace StreamPipeline\Operations;

/**
 * Values operations.
 *
 * This class methods can be used to better readability in Stream flows.
 */
final class Values
{
    /**
     * Checks whether the element is empty.
     * @return callable a callable function.
     */
    public static function isEmpty(): callable
    {
        return function ($element): bool {
            return empty($element);
        };
    }

    /**
     * Checks whether the element is not empty.
     * @return callable a callable function.
     */
    public static function isNotEmpty(): callable
    {
        return function ($element): bool {
            return !empty($element);
        };
    }

    /**
     * Checks whether the element is null.
     * @return callable a callable function.
     */
    public static function isNull(): callable
    {
        return function ($element): bool {
            return is_null($element);
        };
    }

    /**
     * Checks whether the element is not null.
     * @return callable a callable function.
     */
    public static function isNotNull(): callable
    {
        return function ($element): bool {
            return !is_null($element);
        };
    }

    /**
     * Checks whether the element is equal to the provided value.
     * @param mixed $value the value to compare.
     * @param bool $strict if true, checks in strict mode
     * @return callable a callable function.
     */
    public static function equalsTo($value, bool $strict = true): callable
    {
        return function ($element) use ($value, $strict): bool {
            return $strict
                ? $element === $value
                : $element == $value;
        };
    }

    /**
     * Filters a variable with the filter_var function.
     * @param string $filter the filter to apply.
     * @param array|int $options an array defining the arguments.
     * @return callable a callable function.
     * @see filter_var
     */
    public static function filterVar(string $filter, $options = null): callable
    {
        return function ($element) use ($filter, $options) {
            return filter_var($element, $filter, $options ?? 0);
        };
    }
}
