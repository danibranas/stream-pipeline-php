<?php


namespace StreamPipeline\Operations;

/**
 * Logical Operations.
 *
 * This class methods can be used to better readability in Stream flows.
 */
final class Logical
{
    /**
     * An identity operation that returns the same input item as output.
     * @return callable a callable function.
     */
    public static function identity(): callable
    {
        return function ($element) {
            return $element;
        };
    }

    /**
     * Negates the callable passed operation, or the element itself if null.
     * @param callable|null $operation the logical operation to negate.
     * @return callable a callable function.
     */
    public static function not(?callable $operation = null): callable
    {
        return function ($element) use ($operation): bool {
            return !is_null($operation)
                ? !$operation($element)
                : !$element;
        };
    }
}
