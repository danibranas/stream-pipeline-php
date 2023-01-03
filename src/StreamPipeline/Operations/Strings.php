<?php


namespace StreamPipeline\Operations;

/**
 * Strings operations.
 *
 * This class methods can be used to better readability in Stream flows.
 */
final class Strings
{
    /**
     * Gets the string value of an element.
     * @return callable a callable function.
     */
    public static function toString(): callable
    {
        return function ($element) {
            return strval($element);
        };
    }

    /**
     * Gets the trimmed value of a string element.
     * @return callable a callable function.
     */
    public static function trim(): callable
    {
        return function ($element) {
            return trim($element);
        };
    }

    /**
     * Gets a lowercase value of an element.
     * @return callable a callable function.
     */
    public static function toLower(): callable
    {
        return function ($element) {
            return strtolower($element);
        };
    }

    /**
     * Gets an uppercase value of an element.
     * @return callable a callable function.
     */
    public static function toUpper(): callable
    {
        return function ($element) {
            return strtoupper($element);
        };
    }

    /**
     * Uppercase the first character of each word in a string.
     * @return callable a callable function.
     */
    public static function ucwords(): callable
    {
        return function ($element) {
            return ucwords($element);
        };
    }

    /**
     * Concatenates the element to the provided string.
     * @param string $str the provided string to concatenate.
     * @return callable a callable function.
     */
    public static function concat(string $str): callable
    {
        return function ($element) use ($str) {
            return $element . $str;
        };
    }

    /**
     * Return part of a element string.
     * @param int $offset the starting char index.
     * @param int|null $length the desired output length.
     * @return callable a callable function.
     */
    public static function substr(int $offset, int $length = null): callable
    {
        return function ($element) use ($offset, $length) {
            return substr($element, $offset, $length);
        };
    }

    /**
     * Checks if an element starts with the provided string.
     * @param string $needle the provided needle.
     * @return callable a callable function.
     */
    public static function startsWith(string $needle): callable
    {
        return function ($element) use ($needle): bool {
            return function_exists('str_starts_with')
                ? str_starts_with($element, $needle)
                : strncmp($element, $needle, strlen($needle)) === 0;
        };
    }

    /**
     * Checks if an element is a string.
     * @return callable a callable function.
     */
    public static function isString(): callable
    {
        return function ($element): bool {
            return is_string($element);
        };
    }

    /**
     * Replaces all occurrences of the search string with the replacement string.
     * Internally, it uses the <code>str_replace</code> built-in function.
     * @param string|string[] $search The value being searched for, otherwise known as the needle.
     * An array may be used to designate multiple needles.
     * @param string|string[] $replace The replacement value that replaces found search values.
     * An array may be used to designate multiple replacements.
     * @return callable a callable function.
     * @see str_replace()
     */
    public static function replace($search, $replace): callable
    {
        return function ($element) use ($search, $replace) {
            if (!is_string($element) && !is_array($element)) {
                return $element;
            }

            return str_replace($search, $replace, $element);
        };
    }
}
