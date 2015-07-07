<?php

namespace Detail\Filtering\InputFilter\Zend\InputFilter;

use Zend\InputFilter\CollectionInputFilter as BaseCollectionInputFilter;

use Detail\Filtering\Exception;

class CollectionInputFilter extends BaseCollectionInputFilter
{
    public function setInputFilter($inputFilter)
    {
        if (is_string($inputFilter)) {
            $inputFilters = $this->getFactory()->getInputFilterManager();

            $inputFilter = $inputFilters->get($inputFilter);
        }

        return parent::setInputFilter($inputFilter);
    }
}
