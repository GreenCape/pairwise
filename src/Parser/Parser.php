<?php

namespace Richenzi\Pairwise\Parser;

use Richenzi\Pairwise\Exceptions\ValidatorException;

interface Parser
{
    /**
     * Uses given input to create array of parameters.
     *
     * @param mixed $input
     * @param array $options
     * @return mixed
     */
    public function parse($input, $options = []);
}
