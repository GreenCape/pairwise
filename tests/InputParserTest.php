<?php

namespace Richenzi\Pairwise\Tests;

use PHPUnit\Framework\TestCase;
use Richenzi\Pairwise\Exceptions\InvalidNumberOfParameters;
use Richenzi\Pairwise\Exceptions\NotArray;
use Richenzi\Pairwise\Parameter;
use Richenzi\Pairwise\Parser\InputParser;

class InputParserTest extends TestCase
{
    /** @test */
    public function it_throws_exception_when_input_is_not_array()
    {
        $this->expectException(NotArray::class);

        (new InputParser())->parse('not_array');
    }

    /** @test */
    public function it_throws_exception_when_input_array_size_is_less_then_two()
    {
        $this->expectException(InvalidNumberOfParameters::class);

        (new InputParser())->parse(['one']);
    }

    /** @test */
    public function it_throws_exception_when_parameter_is_not_array()
    {
        $this->expectException(NotArray::class);
        $this->expectExceptionMessage('Parameter values must be an array.');

        (new InputParser())->parse(['array' => ['value'], 'not-an-array' => 'value']);
    }

    /** @test */
    public function it_parses_input_into_array_of_parameter_objects()
    {
        $result = (new InputParser())->parse([
            'color'     => ['blue', 'green'],
            'vegetable' => ['carrot', 'potato']
        ]);

        $this->assertCount(2, $result);
        collect($result)->each(function ($item) {
            $this->assertInstanceOf(Parameter::class, $item);
        });
    }

    /** @test */
    public function it_correctly_parses_parameter_name_and_values()
    {
        $result = (new InputParser())->parse([
            'color'     => ['blue', 'green'],
            'vegetable' => ['carrot', 'potato']
        ]);

        $this->assertEquals('color', $result[0]->getName());
        $this->assertEquals(['blue', 'green'], $result[0]->getValues());
    }

    /** @test */
    public function it_correctly_parses_parameters_without_explicit_names()
    {
        $result = (new InputParser())->parse([
            ['blue', 'green'],
            ['carrot', 'potato']
        ]);

        $this->assertEquals(0, $result[0]->getName());
        $this->assertEquals(1, $result[1]->getName());
    }
}
