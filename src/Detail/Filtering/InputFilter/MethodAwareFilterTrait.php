<?php

namespace Detail\Filtering\InputFilter;

trait MethodAwareFilterTrait
{
    /**
     * @var integer
     */
    protected $method;

    /**
     * Set filter's operating method.
     *
     * @param integer $method
     */
    protected function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get filter's operating method.
     *
     * @return integer
     */
    protected function getMethod()
    {
        return $this->method;
    }

    /**
     * Check if the filter's operating method is matched by the given method(s).
     *
     * @param integer $method
     * @return boolean
     * @usage if ($this->matchesMethod(self::METHOD_CREATE) {...}
     * @usage if ($this->matchesMethod(self::METHOD_UPDATE) {...}
     * @usage if ($this->matchesMethod(self::METHOD_CREATE | self::METHOD_UPDATE)) {...}
     */
    protected function matchMethod($method)
    {
        return ($method & $this->getMethod()) == $this->getMethod();
    }
}
