<?php

namespace Richenzi\Pairwise\Exceptions;

class FileNotFound extends ValidatorException
{
    public function __construct(string $filename)
    {
        parent::__construct(sprintf('The file %s  was not found', $filename));
    }
}
