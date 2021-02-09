<?php

namespace Stream\Iterators;

/**
 * Class NumberGenerator
 * @package ArrayStream\Iterators
 */
class NumberGenerator
{
    /** @var int|float */
    private $step;

    /**
     * NumberGenerator constructor.
     * @param int|float $step
     */
    public function __construct($step = 1)
    {
        $this->step = $step;
    }

    /**
     * @param int|float $element
     * @return float|int
     */
    public function __invoke($element)
    {
        return $element + $this->step;
    }
}