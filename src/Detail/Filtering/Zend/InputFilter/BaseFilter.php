<?php

namespace Detail\Filtering\Zend\InputFilter;

use Traversable;

use Zend\InputFilter\Exception\InvalidArgumentException as InputFilterInvalidArgumentException;
use Zend\InputFilter\Exception\ExceptionInterface as InputFilterException;
use Zend\InputFilter\InputFilter;

use Detail\Filtering\InputFilter\FilterInterface;
use Detail\Filtering\Exception;

abstract class BaseFilter extends InputFilter implements
    FilterInterface
{
    /**
     * @var boolean
     */
    protected $required = true;

    /**
     * @var array|null
     */
    protected $inputsToFilter;

    /**
     * Holds the provided data when it's an invalid type.
     *
     * @var mixed
     */
    protected $invalidData;

    /**
     * @var InputFilterInvalidArgumentException
     */
    protected $invalidDataTypeException;

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = (bool) $required;
    }

    /**
     * Set inputs (by name) to filter/validate.
     *
     * null = filter all
     * empty array = filter none
     *
     * @param array|null $inputs
     * @return BaseFilter
     */
    public function setInputsToFilter($inputs)
    {
        // No need to apply validation groups for empty arrays
        if (!is_array($inputs) || $this->needsFiltering($inputs)) {
            try {
                if ($inputs === null) {
                    // Provide Zend's expected value when intending to filter all inputs...
                    $group = self::VALIDATE_ALL;
                } elseif (is_array($inputs)) {
                    $knownInputs = array_keys($this->getInputs());
                    $group = array_intersect($inputs, $knownInputs);
                } else {
                    $group = $inputs;
                }

                parent::setValidationGroup($group);
            } catch (InputFilterException $e) {
                throw new Exception\InvalidArgumentException(
                    sprintf('Failed to set inputs to filter: %s', $e->getMessage()),
                    0,
                    $e
                );
            }
        }

        // Apply only after we made sure the provided inputs are valid
        $this->inputsToFilter = $inputs;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getInputsToFilter()
    {
        return $this->inputsToFilter;
    }

    /**
     * @param array|string|null $name
     * @return BaseFilter
     * @todo Support dynamic parameters (func_get_args)
     */
    public function setValidationGroup($name)
    {
        if ($name === self::VALIDATE_ALL) {
            $name = null;
        }

        $this->setInputsToFilter($name);

        return $this;
    }

    /**
     * Set data to use when validating and filtering
     *
     * @param array|Traversable $data
     * @return BaseFilter
     */
    public function setData($data)
    {
        // setData() expects array, but we can't prevent users from providing other types.
        // So instead of an exception we just want this input filter to be invalid...
        try {
            parent::setData($data);
            $this->invalidData = null;
            $this->invalidDataTypeException = null;
        } catch (InputFilterInvalidArgumentException $e) {
            $this->invalidData = $data;
            $this->invalidDataTypeException = $e;
        }

        return $this;
    }

    /**
     * Is the data set valid?
     *
     * @param mixed|null $context
     * @return boolean
     */
    public function isValid($context = null)
    {
        // When there's an invalid data type, it should immediately report as invalid.
        if ($this->invalidDataTypeException !== null) {
            return false;
        }

        // When there's no data and the filter itself is not required,
        // it should immediately report as valid.
        if (!$this->data && !$this->isRequired()) {
            return true;
        }

        // Don't filter at all when "inputsToFilter" is set to empty array
        if (!$this->needsFiltering()) {
            return true;
        }

        return parent::isValid($context);
    }

    /**
     * Return a list of validation failure messages.
     *
     * @return array
     */
    public function getMessages()
    {
        if ($this->invalidDataTypeException !== null) {
            return array(
                'invalidType' => sprintf(
                    'Value must be an array or Traversable object; received %s',
                    is_object($this->invalidData) ? get_class($this->invalidData) : gettype($this->invalidData)
                )
            );
        }

        return parent::getMessages();
    }

    /**
     * Return a list of filtered values.
     *
     * @return array|null
     */
    public function getValues()
    {
        // When there's no data and the filter itself is not required,
        // we don't return values
        if (!$this->data && !$this->isRequired()) {
            return null;
        }

        // Don't return any values when "inputsToFilter" is set to empty array
        if (!$this->needsFiltering()) {
            return array();
        }

        return parent::getValues();
    }

    /**
     * Do we have to filter at all?
     *
     * @param array $inputs
     * @return bool
     */
    protected function needsFiltering(array $inputs = null)
    {
        $inputsToFilter = $inputs ?: $this->getInputsToFilter();

        return !(is_array($inputsToFilter) && count($inputsToFilter) === 0);
    }
}
