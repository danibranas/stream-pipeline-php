<?php

namespace StreamPipeline;

use StreamPipeline\Operations\Logical;
use Generator;

/**
 * Class Stream
 *
 * Main class for Stream pipeline
 */
class Stream implements StreamInterface
{

    /** @var Generator */
    private $flow;

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
            foreach ($collection as $element) {
                yield $element;
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
            foreach ($this->flow as $i => $item) {
                yield $operation($item, $i);
            }
        });
    }

    /** @inheritDoc */
    public function filter(callable $operation): StreamInterface
    {
        return self::createStream(function () use ($operation): Generator {
            foreach ($this->flow as $i => $item) {
                if ($operation($item, $i)) {
                    yield $item;
                }
            }
        });
    }

    /** @inheritDoc */
    public function peek(callable $operation): StreamInterface
    {
        return self::createStream(function () use ($operation): Generator {
            foreach ($this->flow as $i => $item) {
                $operation($item, $i);
                yield $item;
            }
        });
    }

    /** @inheritDoc */
    public function limit(int $limit): StreamInterface
    {
        return self::createStream(function () use ($limit): Generator {
            $i = 0;
            foreach ($this->flow as $item) {
                if ($i > $limit - 1) {
                    return;
                }

                $i += 1;
                yield $item;
            }
        });
    }

    /** @inheritDoc */
    public function skip(int $number): StreamInterface
    {
        return self::createStream(function () use ($number): Generator {
            $i = 0;
            foreach ($this->flow as $item) {
                if ($i < $number) {
                    $i += 1;
                    continue;
                }

                yield $item;
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
                yield $item;
            }
        });
    }

    /** @inheritDoc */
    public function flatMap(?callable $operation = null): StreamInterface
    {
        $mapItem = $operation ?? Logical::identity();
        return self::createStream(function () use ($mapItem): Generator {
            foreach ($this->flow as $i => $item) {
                yield from $mapItem($item, $i);
            }
        });
    }

    /** @inheritDoc */
    public function concat(iterable $elements): StreamInterface
    {
        return self::createStream(function () use ($elements): Generator {
            foreach ($this->flow as $item) {
                yield $item;
            }

            foreach ($elements as $item) {
                yield $item;
            }
        });
    }

    /** @inheritDoc */
    public function forEach(callable $callback): void
    {
        foreach ($this->flow as $i => $item) {
            $callback($item, $i);
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
        foreach ($this->flow as $i => $item) {
            if ($condition($item, $i)) {
                return true;
            }
        }

        return false;
    }

    /** @inheritDoc */
    public function allMatch(callable $condition): bool
    {
        foreach ($this->flow as $item) {
            if (!$condition($item)) {
                return false;
            }
        }

        return true;
    }

    /** @inheritDoc */
    public function noneMatch(callable $condition): bool
    {
        foreach ($this->flow as $i => $item) {
            if ($condition($item, $i)) {
                return false;
            }
        }

        return true;
    }

    /** @inheritDoc */
    public function reduce(callable $operation, $initialValue)
    {
        $accumulator = $initialValue;

        foreach ($this->flow as $i => $item) {
            $accumulator = $operation($accumulator, $item, $i);
        }

        return $accumulator;
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->flow as $item) {
            $result[] = $item;
        }

        return $result;
    }

    /** @inheritDoc */
    public function collect(callable $collector)
    {
        $accumulator = $this->flow->current();
        $this->flow->next();

        while ($this->flow->valid()) {
            $accumulator = $collector($accumulator, $this->flow->current());
            $this->flow->next();
        }

        return $accumulator;
    }

    /** @inheritDoc */
    public function getIterator()
    {
        return $this->flow;
    }

    private static function createStream(callable $operation): StreamInterface
    {
        return new self($operation());
    }
}