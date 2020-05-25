<?php

namespace Richenzi\Pairwise\Tests;

use PHPUnit\Framework\TestCase;
use Richenzi\Pairwise\Parameter;
use Richenzi\Pairwise\Parser\FileParser;
use Richenzi\Pairwise\TestCaseGenerator;

class TestCaseGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_test_cases_with_size_equal_to_number_of_parameters()
    {
        $parameters = [
            new Parameter('number', ['one', 'two', 'three']),
            new Parameter('color', ['blue', 'green']),
            new Parameter('vegetable', ['carrot', 'potato'])
        ];

        collect((new TestCaseGenerator($parameters))->generate())
            ->each(function ($testCase) {
                $this->assertCount(3, $testCase);
            });
    }

    /** @test */
    public function it_generates_test_cases_covering_all_pairs()
    {
        $testDirectory = __DIR__ . '/files/generator/';

        collect(array_diff(scandir($testDirectory), ['.', '..']))
            ->map(function ($fileName) use ($testDirectory) {
                return $testDirectory . 'test_3.txt';
            })->each(function ($testFilePath) {
                $parameters = (new FileParser())->parse($testFilePath);
                $generator = new TestCaseGenerator($parameters);
                $this->assertTrue((new GeneratorValidator())->validate($generator, $parameters));
            });
    }
}
