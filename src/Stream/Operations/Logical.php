<?php


namespace Stream\Operations;


final class Logical
{
    public static function identity(): callable
    {
        return function ($element) {
            return $element;
        };
    }

    public static function not(?callable $operation = null): callable
    {
        return function ($element) use ($operation): bool {
            return !is_null($operation)
                ? !$operation($element)
                : !$element;
        };
    }
}