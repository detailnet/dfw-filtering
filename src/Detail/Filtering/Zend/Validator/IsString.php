<?php

namespace Detail\Filtering\InputFilter\Zend\Validator;

use Zend\Validator\AbstractValidator as BaseValidator;

class IsString extends BaseValidator
{
    const INVALID_VALUE = 'notString';

    protected $messageTemplates = array(
        self::INVALID_VALUE => 'Value is not a string',
    );

    public function isValid($value)
    {
        $this->setValue($value);

        if (!is_string($value)) {
            $this->error(self::INVALID_VALUE);
            return false;
        }

        return true;
    }
}
