<?php

namespace Detail\Filtering\InputFilter;

interface MethodAwareFilterInterface
{
    // Methods numbering has to be 2^n (to allow bitwise operations)
    const METHOD_UPDATE = 1;
    const METHOD_CREATE = 2;
}
