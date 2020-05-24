<?php

namespace Richenzi\Pairwise\Tests;

use PHPUnit\Framework\TestCase;
use Richenzi\Pairwise\Dataset;
use Richenzi\Pairwise\Parameter;

class DatasetTest extends TestCase
{
    private $parameters;

    protected function setUp(): void
    {
        $this->parameters = [
            new Parameter('number', ['one', 'two', 'three']),
            new Parameter('color', ['blue', 'green'])
        ];
    }

    /**
     * @test
     * @see Dataset::$values
     */
    public function it_flattens_all_values_into_one_array()
    {
        $this->assertEquals(
            ['one', 'two', 'three', 'blue', 'green'],
            (new Dataset($this->parameters))->getValues()
        );
    }

    /**
     * @test
     * @see Dataset::$valueIndices
     */
    public function it_creates_a_copy_of_parameter_values_with_indices_instead_of_their_original_values()
    {
        $this->assertEquals(
            [[0, 1, 2], [3, 4]],
            (new Dataset($this->parameters))->getValueIndices()
        );
    }

    /**
     * @test
     * @see Dataset::$parameterIndexMappings
     */
    public function it_creates_an_array_for_mapping_each_value_to_the_index_of_its_parameter()
    {
        $this->assertEquals(
            [0, 0, 0, 1, 1],
            (new Dataset($this->parameters))->getParameterIndexMappings()
        );
    }

    /** @test */
    public function it_returns_total_number_of_parameters()
    {
        $this->assertEquals(2, (new Dataset($this->parameters))->getTotalNumberOfParameters());
    }

    /** @test */
    public function it_returns_total_number_of_values()
    {
        $this->assertEquals(5, (new Dataset($this->parameters))->getTotalNumberOfValues());
    }
}
