<?php

namespace Detail\Filtering\InputFilter;

use Detail\Filtering\Exception;

trait FilterAwareTrait
{
    /**
     * @var FilterInterface[]
     */
    protected $inputFilters;

    /**
     * @param string $name
     * @return FilterInterface|null
     */
    public function getInputFilter($name)
    {
        $inputFilters = $this->getInputFilters();

        return isset($inputFilters[$name]) ? $inputFilters[$name] : null;
    }

    /**
     * @return FilterInterface[]
     */
    public function getInputFilters()
    {
        return $this->inputFilters;
    }

    /**
     * @param FilterInterface[] $inputFilters
     */
    public function setInputFilters(array $inputFilters)
    {
        $this->inputFilters = $inputFilters;
    }

    /**
     * @param string $filter
     * @param array $data
     * @param array|null
     * @return array
     */
    protected function getFilteredData($filter, array $data, $inputsToFilter = null)
    {
        $filter = $this->getInputFilter($filter);

        if ($filter !== null) {
            $filter->setInputsToFilter($inputsToFilter);
            $filter->setData($data);

            if ($filter->isValid()) {
                $data = $filter->getValues();
            } else {
                throw Exception\FilterException::fromMessages($filter->getMessages());
            }
        }

        /** @todo When no filter is found then the same input data is returned. Should not raise exception instead? */

        return $data;
    }
}
