<?php

namespace Richenzi\Pairwise\Exceptions;

class InvalidNumberOfParameters extends ValidatorException
{
    public function __construct()
    {
        parent::__construct('Must provide at least two parameters.');
    }
}
