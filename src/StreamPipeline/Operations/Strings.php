<?php


namespace StreamPipeline\Operations;


final class Strings
{
    public static function toString(): callable
    {
        return function ($element) {
            return strval($element);
        };
    }

    public static function trim(): callable
    {
        return function ($element) {
            return trim($element);
        };
    }

    public static function toLower(): callable
    {
        return function ($element) {
            return strtolower($element);
        };
    }

    public static function toUpper(): callable
    {
        return function ($element) {
            return strtoupper($element);
        };
    }

    public static function ucwords(): callable
    {
        return function ($element) {
            return ucwords($element);
        };
    }

    public static function concat(string $str): callable
    {
        return function ($element) use ($str) {
            return $element . $str;
        };
    }

    public static function substr(int $offset, int $length = null): callable
    {
        return function ($element) use ($offset, $length) {
            return substr($element, $offset, $length);
        };
    }

    public static function startsWith(string $needle): callable
    {
        return function ($element) use ($needle): bool {
            return function_exists('str_starts_with')
                ? str_starts_with($element, $needle)
                : strncmp($element, $needle, strlen($needle)) === 0;
        };
    }

    public static function isString(): callable
    {
        return function ($element): bool {
            return is_string($element);
        };
    }

}