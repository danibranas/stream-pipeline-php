<?php

namespace StreamPipeline\Iterators;

/**
 * A number generator.
 */
class NumberGenerator
{
    /** @var int|float */
    private $step;

    /**
     * NumberGenerator constructor.
     * @param int|float $step the step to get the next number in the generator.
     */
    public function __construct($step = 1)
    {
        $this->step = $step;
    }

    /**
     * Gets the next element in the series.
     * @param int|float $element the current element.
     * @return float|int the next element.
     */
    public function __invoke($element)
    {
        return $element + $this->step;
    }
}
