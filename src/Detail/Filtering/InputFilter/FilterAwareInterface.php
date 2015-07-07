<?php

namespace Detail\Filtering\InputFilter;

interface FilterAwareInterface
{
    /**
     * @param FilterInterface[] $inputFilters
     */
    public function setInputFilters(array $inputFilters);
}
