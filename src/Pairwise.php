<?php

namespace Richenzi\Pairwise;

use Richenzi\Pairwise\Exceptions\ValidatorException;
use Richenzi\Pairwise\Parser\FileParser;
use Richenzi\Pairwise\Parser\InputParser;

class Pairwise
{
    /**
     * Returns new instance of the generator fed with data
     * parsed by the input parser.
     *
     * @param $input
     * @param array $options for parser
     * @return TestCaseGenerator
     * @throws ValidatorException
     */
    public static function fromData($input, $options = [])
    {
        return new TestCaseGenerator(
            (new InputParser())->parse($input, $options)
        );
    }

    /**
     * Returns new instance of the generator fed with data
     * parsed by the file parser.
     *
     * @param $path
     * @param array $options for parser
     * @return TestCaseGenerator
     * @throws ValidatorException
     */
    public static function fromFile($path, $options = [])
    {
        return new TestCaseGenerator(
            (new FileParser())->parse($path, $options)
        );
    }
}
