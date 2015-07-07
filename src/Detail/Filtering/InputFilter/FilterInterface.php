<?php

namespace Detail\Filtering\InputFilter;

use Traversable;

interface FilterInterface
{
    /**
     * Set data to use when validating and filtering
     *
     * @param array|Traversable $data
     */
    public function setData($data);

    /**
     * Specify the inputs (by name) to filter/validate.
     *
     * @param array|null $inputs
     */
    public function setInputsToFilter($inputs);

    /**
     * Return a list of inputs to filter/validate.
     *
     * List should be an array of named inputs.
     *
     * @return array|null
     */
    public function getInputsToFilter();

    /**
     * Is the data set valid?
     *
     * @return bool
     */
    public function isValid();

    /**
     * Retrieve a value from a named input
     *
     * @param string $name
     * @return mixed
     */
    public function getValue($name);

    /**
     * Return a list of filtered values
     *
     * List should be an associative array, with the values filtered. If
     * validation failed, this should raise an exception.
     *
     * @return array
     */
    public function getValues();

    /**
     * Retrieve a raw (unfiltered) value from a named input
     *
     * @param string $name
     * @return mixed
     */
    public function getRawValue($name);

    /**
     * Return a list of unfiltered values
     *
     * List should be an associative array of named input/value pairs,
     * with the values unfiltered.
     *
     * @return array
     */
    public function getRawValues();

    /**
     * Return a list of validation failure messages
     *
     * Should return an associative array of named input/message list pairs.
     * Pairs should only be returned for inputs that failed validation.
     *
     * @return array
     */
    public function getMessages();
}
