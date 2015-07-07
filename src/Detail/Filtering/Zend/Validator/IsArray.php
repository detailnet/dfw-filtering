<?php

namespace Detail\Filtering\Zend\Validator;

use Zend\Validator\AbstractValidator as BaseValidator;

class IsArray extends BaseValidator
{
    const INVALID_VALUE = 'notArray';

    protected $messageTemplates = array(
        self::INVALID_VALUE => 'Value is not an array',
    );

    public function isValid($value)
    {
        $this->setValue($value);

        if (!is_array($value)) {
            $this->error(self::INVALID_VALUE);
            return false;
        }

        return true;
    }
}
