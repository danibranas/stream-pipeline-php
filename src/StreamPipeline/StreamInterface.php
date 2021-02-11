<?php

namespace StreamPipeline;

use Countable;

/**
 * Interface StreamInterface
 *
 * A sequence of elements supporting sequential and aggregate operations.
 */
interface StreamInterface extends Countable
{
    /**
     * Returns a pipeline whose elements are the specified values.
     *
     * @param mixed ...$elements elements of the pipeline
     * @return StreamInterface the new pipeline.
     */
    public static function of(...$elements): StreamInterface;

    /**
     * Returns a pipeline whose elements are the elements from the specified iterable.
     *
     * @param iterable $collection elements of the pipeline
     * @return StreamInterface the new pipeline.
     */
    public static function fromIterable(iterable $collection): StreamInterface;

    /**
     * Returns an infinite pipeline whose elements are numbers generated by iterating through an initial value and a
     * step operation.
     *
     * @param int|float $initialValue the initial value.
     * @param callable $stepOperation a function that receives the current value and returns the next value.
     * @return StreamInterface the new pipeline.
     */
    public static function iterate($initialValue, callable $stepOperation): StreamInterface;

    /**
     * Returns a pipeline whose elements are the results of applying the given function to the elements of this stream.
     *
     * The function receives the element as first parameter and the key as second parameter.
     *
     * @param callable $operation The function to apply to each element.
     * @return StreamInterface the new pipeline.
     */
    public function map(callable $operation): StreamInterface;

    /**
     * Returns a pipeline consisting of the elements of this pipeline that match the given function.
     *
     * @param callable $operation the filter function that receives the value and returns a boolean.
     * @return StreamInterface the new pipeline.
     */
    public function filter(callable $operation): StreamInterface;

    /**
     * Returns a pipeline consisting of the elements of this stream, performing the provided action on each element.
     *
     * The main purpose of this method is to support debugging.
     *
     * @param callable $operation a function that receives each element. The returned value is ignored
     * @return StreamInterface the new pipeline.
     */
    public function peek(callable $operation): StreamInterface;

    /**
     * Returns a pipeline consisting of the n first elements.
     *
     * @param int $limit maximum number of elements
     * @return StreamInterface the new pipeline
     */
    public function limit(int $limit): StreamInterface;

    /**
     * Discards the first n items and returns a pipeline consisting of the remaining elements.
     *
     * @param int $number number of elements to skip
     * @return StreamInterface the new pipeline
     */
    public function skip(int $number): StreamInterface;

    /**
     * Returns a pipeline consisting of the distinct elements.
     * If a distinct function is provided (for example, a <code>trim</code> function), it is used to convert the
     * element before the comparison. It does not affect the elements themselves.
     *
     * @param callable|null $distinctBy an optional function to convert the element.
     * @return StreamInterface the new pipeline
     */
    public function distinct(?callable $distinctBy = null): StreamInterface;

    /**
     * Returns the first element of this pipeline.
     *
     * @return mixed|null the first element found or null otherwise
     */
    public function findFirst();

    /** @inheritDoc */
    public function count(): int;

    /**
     * Iterate over the elements of this pipeline.
     * The callback function is called one time for each element and receives as parameters the element and the index.
     *
     * @param callable $callback the callback function
     */
    public function forEach(callable $callback): void;

    /**
     * Returns <code>true</code> if any element of this pipeline match the given condition, <code>false</code>
     * otherwise.
     *
     * @param callable $condition the check function. Receives the element as a parameter and must return a bool
     * @return bool the result of the operation
     */
    public function anyMatch(callable $condition): bool;

    /**
     * Returns <code>true</code> if all the elements of this pipeline match the given condition, <code>false</code>
     * otherwise.
     *
     * @param callable $condition the check function. Receives the element as a parameter and must return a bool
     * @return bool the result of the operation
     */
    public function allMatch(callable $condition): bool;

    /**
     * Returns <code>true</code> if no elements of this pipeline match the given condition, <code>false</code>
     * otherwise.
     *
     * @param callable $condition the check function. Receives the element as a parameter and must return a bool
     * @return bool the result of the operation
     */
    public function noneMatch(callable $condition): bool;

    /**
     * Executes a reducer function on each processed element of the pipeline, resulting in a single output value.
     *
     * @param callable $operation the reducer function. Receives the accumulator and the current element as params and
     * must return the new accumulator value
     * @param mixed $initialValue initial value
     * @return mixed the result value.
     */
    public function reduce(callable $operation, $initialValue);

    /**
     * Returns an array containing the elements of this pipeline.
     *
     * @return array the result value.
     */
    public function toArray(): array;

    /**
     * Executes a collector function on each processed element of the pipeline, resulting in a single output value.
     * This method is similar to {@see StreamInterface::reduce()}, but does not require an initial value.
     *
     * @param callable $collector the collector function.
     * @return mixed the result value.
     */
    public function collect(callable $collector);
}