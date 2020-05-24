<?php

namespace Richenzi\Pairwise\Tests;

use Richenzi\Pairwise\Dataset;
use Richenzi\Pairwise\PairRepository;
use Richenzi\Pairwise\TestCaseGenerator;

class GeneratorValidator
{
    public function validate(TestCaseGenerator $generator, $parameters)
    {
        $pairs = $this->getTranslatedPairsFromParameters($parameters);
        $testCases = $generator->generate();

        foreach ($pairs as $pair) {
            $pairInTestCase = false;
            foreach ($testCases as $testCase) {
                if ($this->areBothPairValuesInGivenTestCase($pair, $testCase)) {
                    $pairInTestCase = true;
                    break;
                }
            }
            if (!$pairInTestCase) {
                return false;
            }
        }
        return true;
    }

    private function getTranslatedPairsFromParameters($parameters)
    {
        return collect((new PairRepository($dataset = new Dataset($parameters)))->getPairs())
            ->map(function ($pair) use ($dataset) {
                return [
                    $dataset->getValues()[$pair[0]],
                    $dataset->getValues()[$pair[1]]
                ];
            })->toArray();
    }

    private function areBothPairValuesInGivenTestCase($pair, $testCase)
    {
        return in_array($pair[0], $testCase) && in_array($pair[1], $testCase);
    }
}
