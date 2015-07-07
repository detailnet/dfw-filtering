<?php

namespace Detail\Filtering\InputFilter\Zend\Validator;

use Rhumsaa\Uuid\Uuid;

use Zend\Validator\AbstractValidator as BaseValidator;
use Zend\Validator\Exception;

class UuidV4 extends BaseValidator
{
    const UUID_V4_PATTERN = '[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(8|9|a|b)[a-f0-9]{3}\-[a-f0-9]{12}';

    const INVALID     = 'inputInvalid';
    const NOT_UUID_V4 = 'uuidInvalid';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID     => 'Invalid type given. String or UUID expected',
        self::NOT_UUID_V4 => 'The input does not appear to be a valid UUID Version 4',
    );

    /**
     * Returns true if and only if $value is a valid Uuid Version 4 string
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value)
            && !($value instanceof Uuid) // If instance of Uuid still does not means that is valid
        ) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);

        if (!preg_match('/^' . self::UUID_V4_PATTERN . '$/', $value)) {
            $this->error(self::NOT_UUID_V4);
            return false;
        }

        return true;
    }
}
