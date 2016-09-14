<?php

namespace Detail\Filtering\Zend\Validator;

use Zend\Validator\AbstractValidator as BaseValidator;
use Zend\Validator\Exception;

class IsArrayOfClass extends BaseValidator
{
    const INVALID_CLASS   = 'invalid_class';
    const INVALID_ARRAY   = 'invalid_array';
    const INVALID_ELEMENT = 'invalid_element';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID_ARRAY   => 'Value is not an array',
        self::INVALID_ELEMENT => 'One or more elements of array are not an instance of %class% class',
    );

    /**
     * @var array
     */
    protected $messageVariables = array(
        'class' => array('options' => 'class'),
    );

    /**
     * Default options to set for the validator
     *
     * @var mixed
     */
    protected $options = array(
        'class' => null, // The class to check against
    );

    /**
     * Constructor
     *
     * @param array|string $options
     */
    public function __construct($options)
    {
        if (is_string($options)) {
            $this->setClass($options);
            $options = null;
        }

        parent::__construct($options);
    }

    /**
     * Returns the set class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->options['class'];
    }

    /**
     * Sets the class
     *
     * @param string $class
     * @return self Provides a fluent interface
     * @throws Exception\InvalidArgumentException
     */
    public function setClass($class)
    {
        if (!(class_exists($class) || interface_exists($class))) {
            throw new Exception\InvalidArgumentException(
                sprintf('Invalid class/interface given: %s', $class)
            );
        }

        $this->options['class'] = $class;
        return $this;
    }

    /**
     * @param mixed $value
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function isValid($value)
    {
        $this->setValue($value);

        $class = $this->getClass();

        if (empty($class)) {
            throw new Exception\InvalidArgumentException('No class given');
        }

        if (!is_array($value)) { //|| !($value instanceof Traversable)) {
            $this->error(self::INVALID_ARRAY);
            return false;
        }

        // Function to check all items of an array are of the same same class
        // ~= array_reduce(<<Array>>, function ($carry, $item) { return $carry && ($item instanceof <<Class>>);}, true)
        $arrayValuesSubclassOf = function ($array, $className) {
            return array_reduce(
                $array,
                function ($carry, $item) use ($className) {
                    return $carry && is_a($item, $className);
                },
                true
            );
        };

        if (!$arrayValuesSubclassOf($value, $class)) {
            $this->error(self::INVALID_ELEMENT);
            return false;
        }

        return true;
    }
}
