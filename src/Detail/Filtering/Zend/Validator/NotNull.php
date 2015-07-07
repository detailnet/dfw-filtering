<?php

namespace Detail\Filtering\Zend\Validator;

use Zend\Validator\NotEmpty;

class NotNull extends NotEmpty
{
    public function __construct($options = null)
    {
        parent::__construct(self::NULL);

        $this->setMessage("Value can't be null", self::IS_EMPTY);
    }
}
