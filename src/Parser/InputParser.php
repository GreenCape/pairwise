<?php

namespace Richenzi\Pairwise\Parser;

use Richenzi\Pairwise\Exceptions\NotArray;
use Richenzi\Pairwise\Exceptions\InvalidNumberOfParameters;
use Richenzi\Pairwise\Parameter;

class InputParser implements Parser
{
    /**
     * {@inheritDoc}
     */
    public function parse($input, $options = [])
    {
        if (!is_array($input)) {
            throw new NotArray();
        }

        if (count($input) < 2) {
            throw new InvalidNumberOfParameters();
        }

        $parameters = [];

        foreach ($input as $name => $values) {
            if (!is_array($values)) {
                throw new NotArray('Parameter values must be an array.');
            }
            $parameters[] = new Parameter($name, $values);
        }

        return $parameters;
    }
}
