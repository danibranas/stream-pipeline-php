<?php


namespace StreamPipeline\Operations;

/**
 * Numbers operations.
 *
 * This class methods can be used to better readability in Stream flows.
 */
final class Numbers
{
    /**
     * Gets the integer value of an element.
     * @return callable a callable function.
     */
    public static function toInt(): callable
    {
        return function ($element): int {
            return intval($element);
        };
    }

    /**
     * Gets the float value of an element.
     * @return callable a callable function.
     */
    public static function toFloat(): callable
    {
        return function ($element): float {
            return floatval($element);
        };
    }

    /**
     * Checks if the element is an odd number.
     * @return callable a callable function.
     */
    public static function isOdd(): callable
    {
        return function ($element): bool {
            return $element % 2 !== 0;
        };
    }

    /**
     * Checks if the element is an even number.
     * @return callable a callable function.
     */
    public static function isEven(): callable
    {
        return function ($element): bool {
            return $element % 2 === 0;
        };
    }

    /**
     * Checks if the element is numeric.
     * @return callable a callable function.
     */
    public static function isNumeric(): callable
    {
        return function ($element) {
            return is_numeric($element);
        };
    }

    /**
     * Checks if the element is greater than the passed number.
     * @param int|float $number the number to compare with.
     * @return callable a callable function.
     */
    public static function isGreaterThan($number): callable
    {
        return function ($element) use ($number) {
            return $element > $number;
        };
    }

    /**
     * Checks if the element is less than the passed number.
     * @param int|float $number the number to compare with.
     * @return callable a callable function.
     */
    public static function isLessThan($number): callable
    {
        return function ($element) use ($number) {
            return $element < $number;
        };
    }

    /**
     * Checks if the element is less or equals than the passed number.
     * @param int|float $number the number to compare with.
     * @return callable a callable function.
     */
    public static function isLessOrEqualThan($number): callable
    {
        return function ($element) use ($number) {
            return $element <= $number;
        };
    }

    /**
     * Checks if the element is greater or equals than the passed number.
     * @param int|float $number the number to compare with.
     * @return callable a callable function.
     */
    public static function isGreaterOrEqualThan($number): callable
    {
        return function ($element) use ($number) {
            return $element >= $number;
        };
    }

    /**
     * Sums the element to the passed number.
     * @param int|float $number the number to sum.
     * @return callable a callable function.
     */
    public static function plus($number): callable
    {
        return function ($element) use ($number) {
            return $number + $element;
        };
    }

    /**
     * Multiples the element with the passed number.
     * @param int|float $number the number to multiply.
     * @return callable a callable function.
     */
    public static function multiply($number): callable
    {
        return function ($element) use ($number) {
            return $number * $element;
        };
    }
}
