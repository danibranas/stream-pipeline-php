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

    /**
     * Checks whether the element value exists in an array.
     * @param array $values the matching values.
     * @param bool $strict if true, the comparison is strict.
     * @return callable a callable function.
     */
    public static function in(array $values, bool $strict = false): callable
    {
        $keys = self::getKeySet($values);
        return function ($element) use ($keys, $strict): bool {
            return $strict
                ? isset($keys[$element]) && $keys[$element] === $element
                : isset($keys[$element]) && $keys[$element] == $element;
        };
    }

    /**
     * Checks whether the element original key exists in an array.
     * @param array $values the matching values.
     * @return callable a callable function.
     */
    public static function keyIn(array $values): callable
    {
        $keys = self::getKeySet($values);
        return function ($element, $i, $key) use ($keys): bool {
            return isset($keys[$key]) && $keys[$key] == $key;
        };
    }

    private static function getKeySet(array $values): array
    {
        $keys = [];

        foreach ($values as $value) {
            $keys[$value] = $value;
        }

        return $keys;
    }
}
