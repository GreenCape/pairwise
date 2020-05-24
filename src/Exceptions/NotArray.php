<?php

namespace Richenzi\Pairwise\Exceptions;

class NotArray extends ValidatorException
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? 'Input must be an array.');
    }
}
