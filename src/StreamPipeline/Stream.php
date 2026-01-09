<?php

namespace StreamPipeline;

use Iterator;
use StreamPipeline\Operations\Logical;
use Generator;

/**
 * Class Stream
 *
 * Main class for Stream pipeline
 */
class Stream implements StreamInterface
{
    private Generator $flow;

    public function __construct(Generator $flow)
    {
        $this->flow = $flow;
    }

    /** @inheritDoc */
    public static function of(...$elements): StreamInterface
    {
        return self::fromIterable($elements);
    }

    /** @inheritDoc */
    public static function fromIterable(iterable $collection): StreamInterface
    {
        return self::createStream(function () use ($collection): Generator {
            foreach ($collection as $key => $element) {
                yield $key => $element;
            }
        });
    }

    /** @inheritDoc */
    public static function iterate($initialValue, callable $stepOperation): StreamInterface
    {
        return self::createStream(function () use ($initialValue, $stepOperation): Generator {
            $currentValue = $initialValue;

            while (true) {
                yield $currentValue;
                $currentValue = $stepOperation($currentValue);
            }
        });
    }

    /** @inheritDoc */
    public function map(callable $operation): StreamInterface
    {
        return self::createStream(function () use ($operation): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                yield $key => $operation($item, $i++, $key);
            }
        });
    }

    /** @inheritDoc */
    public function filter(callable $operation): StreamInterface
    {
        return self::createStream(function () use ($operation): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                if ($operation($item, $i++, $key)) {
                    yield $key => $item;
                }
            }
        });
    }

    /** @inheritDoc */
    public function peek(callable $operation): StreamInterface
    {
        return self::createStream(function () use ($operation): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                $operation($item, $i++, $key);
                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function limit(int $limit): StreamInterface
    {
        return self::createStream(function () use ($limit): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                if ($i > $limit - 1) {
                    return;
                }

                $i += 1;
                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function skip(int $number): StreamInterface
    {
        return self::createStream(function () use ($number): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                if ($i < $number) {
                    $i += 1;
                    continue;
                }

                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function distinct(?callable $distinctBy = null): StreamInterface
    {
        $getKey = $distinctBy ?? Logical::identity();
        return self::createStream(function () use ($getKey): Generator {
            $elements = [];
            foreach ($this->flow as $item) {
                $key = $getKey($item);
                if (isset($elements[$key])) {
                    continue;
                }

                $elements[$key] = true;
                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function flatMap(?callable $operation = null): StreamInterface
    {
        $mapItem = $operation ?? Logical::identity();
        return self::createStream(function () use ($mapItem): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                yield from $mapItem($item, $i++, $key);
            }
        });
    }

    /** @inheritDoc */
    public function concat(iterable $elements): StreamInterface
    {
        return self::createStream(function () use ($elements): Generator {
            foreach ($this->flow as $key => $item) {
                yield $key => $item;
            }

            foreach ($elements as $key => $item) {
                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function forEach(callable $callback): void
    {
        $i = 0;
        foreach ($this->flow as $key => $item) {
            $callback($item, $i++, $key);
        }
    }

    /** @inheritDoc */
    public function findFirst()
    {
        foreach ($this->flow as $item) {
            return $item;
        }

        return null;
    }

    /** @inheritDoc */
    public function count(): int
    {
        return iterator_count($this->flow);
    }

    /** @inheritDoc */
    public function anyMatch(callable $condition): bool
    {
        $i = 0;
        foreach ($this->flow as $key => $item) {
            if ($condition($item, $i++, $key)) {
                return true;
            }
        }

        return false;
    }

    /** @inheritDoc */
    public function allMatch(callable $condition): bool
    {
        $i = 0;
        foreach ($this->flow as $key => $item) {
            if (!$condition($item, $i++, $key)) {
                return false;
            }
        }

        return true;
    }

    /** @inheritDoc */
    public function noneMatch(callable $condition): bool
    {
        $i = 0;
        foreach ($this->flow as $key => $item) {
            if ($condition($item, $i++, $key)) {
                return false;
            }
        }

        return true;
    }

    /** @inheritDoc */
    public function reduce(callable $operation, $initialValue)
    {
        $accumulator = $initialValue;

        $i = 0;
        foreach ($this->flow as $key => $item) {
            $accumulator = $operation($accumulator, $item, $i++, $key);
        }

        return $accumulator;
    }

    /** @inheritDoc */
    public function toArray(bool $preserveKeys = false): array
    {
        $result = [];

        foreach ($this->flow as $key => $item) {
            if ($preserveKeys) {
                $result[$key] = $item;
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    /** @inheritDoc */
    public function collect(callable $collector)
    {
        $index = 0;
        $firstItem = $this->flow->current();

        $accumulator = $collector(null, $firstItem, $index);
        $this->flow->next();

        while ($this->flow->valid()) {
            $accumulator = $collector($accumulator, $this->flow->current(), ++$index);
            $this->flow->next();
        }

        return $accumulator;
    }

    /** @inheritDoc */
    public function takeWhile(callable $callback): StreamInterface
    {
        return self::createStream(function () use ($callback): Generator {
            $i = 0;
            foreach ($this->flow as $key => $item) {
                if (!$callback($item, $i++, $key)) {
                    // Stop processing remaining elements
                    return;
                }

                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function dropWhile(callable $callback): StreamInterface
    {
        return self::createStream(function () use ($callback): Generator {
            $i = 0;
            $matched = false;
            foreach ($this->flow as $key => $item) {
                if (!$matched && $callback($item, $i++, $key)) {
                    continue;
                }

                $matched = true;
                yield $key => $item;
            }
        });
    }

    /** @inheritDoc */
    public function getIterator(): Iterator
    {
        return $this->flow;
    }

    private static function createStream(callable $operation): StreamInterface
    {
        return new self($operation());
    }
}
