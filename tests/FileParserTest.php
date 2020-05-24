<?php

namespace Richenzi\Pairwise\Tests;

use PHPUnit\Framework\TestCase;
use Richenzi\Pairwise\Exceptions\FileNotFound;
use Richenzi\Pairwise\Exceptions\InvalidNumberOfParameters;
use Richenzi\Pairwise\Exceptions\NotArray;
use Richenzi\Pairwise\Parameter;
use Richenzi\Pairwise\Parser\FileParser;
use Richenzi\Pairwise\Parser\InputParser;

class FileParserTest extends TestCase
{
    /** @test */
    public function it_throws_exception_when_file_is_not_found()
    {
        $this->expectException(FileNotFound::class);

        (new FileParser())->parse('not-existing-file.txt');
    }

    /** @test */
    public function it_parses_file_into_array_of_parameter_objects()
    {
        $result = (new FileParser())->parse(__DIR__ . '/files/parser/parse_tab.txt');

        $this->assertCount(3, $result);
        collect($result)->each(function ($item) {
            $this->assertInstanceOf(Parameter::class, $item);
        });
    }

    /** @test */
    public function it_can_parse_file_with_different_delimiter()
    {
        $result = (new FileParser())->parse(
            __DIR__ . '/files/parser/parse_semicolon.txt',
            ['delimiter' => ';']
        );

        $this->assertCount(3, $result);
        collect($result)->each(function ($item) {
            $this->assertInstanceOf(Parameter::class, $item);
        });
    }

    /** @test */
    public function it_correctly_parses_parameter_name_and_values()
    {
        $result = (new FileParser())->parse(__DIR__ . '/files/parser/parse_tab.txt');

        $this->assertEquals(0, $result[0]->getName());
        $this->assertEquals(['one', 'two', 'three'], $result[0]->getValues());
    }
}
