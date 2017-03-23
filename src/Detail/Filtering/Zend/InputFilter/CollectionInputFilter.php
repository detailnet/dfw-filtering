<?php

namespace Detail\Filtering\Zend\InputFilter;

use Traversable;

use Zend\InputFilter\BaseInputFilter;
use Zend\InputFilter\CollectionInputFilter as BaseCollectionInputFilter;

class CollectionInputFilter extends BaseCollectionInputFilter
{
    /**
     * @param BaseInputFilter|array|Traversable $inputFilter
     * @return CollectionInputFilter
     */
    public function setInputFilter($inputFilter)
    {
        if (is_string($inputFilter)) {
            $inputFilters = $this->getFactory()->getInputFilterManager();
            $inputFilter = $inputFilters->get($inputFilter);
        }

        parent::setInputFilter($inputFilter);

        return $this;
    }
}
