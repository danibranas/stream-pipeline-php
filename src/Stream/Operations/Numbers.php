<?php


namespace Stream\Operations;


final class Numbers
{

    public static function toInt(): callable
    {
        return function ($element): int {
            return intval($element);
        };
    }

    public static function toFloat(): callable
    {
        return function ($element): float {
            return floatval($element);
        };
    }

    public static function isOdd(): callable
    {
        return function ($element): bool {
            return $element % 2 !== 0;
        };
    }

    public static function isEven(): callable
    {
        return function ($element): bool {
            return $element % 2 === 0;
        };
    }

    public static function isNumeric(): callable
    {
        return function ($element) {
            return is_numeric($element);
        };
    }

    public static function isGreaterThan($number): callable
    {
        return function ($element) use ($number) {
            return $element > $number;
        };
    }

    public static function isLessThan($number): callable
    {
        return function ($element) use ($number) {
            return $element < $number;
        };
    }

    public static function isLessOrEqualThan($number): callable
    {
        return function ($element) use ($number) {
            return $element <= $number;
        };
    }

    public static function isGreaterOrEqualThan($number): callable
    {
        return function ($element) use ($number) {
            return $element >= $number;
        };
    }

    public static function plus($number): callable
    {
        return function ($element) use ($number) {
            return $number + $element;
        };
    }

    public static function multiply($number): callable
    {
        return function ($element) use ($number) {
            return $number * $element;
        };
    }
}