<?php

namespace Richenzi\Pairwise;

class TestCaseGenerator
{
    /**
     * Holds an instance of the dataset.
     *
     * @var Dataset
     */
    private Dataset $dataset;

    /**
     * Holds an instance of the pair repository.
     *
     * @var PairRepository
     */
    private PairRepository $pairRepository;


    /**
     * Creates new generator instance.
     *
     * @param Parameter[] $parameters
     */
    public function __construct($parameters)
    {
        $this->dataset = new Dataset($parameters);
        $this->pairRepository = new PairRepository($this->dataset);
    }

    public function generate()
    {
        return $this->createTestCases();
    }

    private function createTestCases()
    {
        $testCases = [];

        while (!$this->pairRepository->isEmpty()) {
            $testCases[] = $this->translateTestCaseToOriginalValues(
                $newTestCase = $this->createSingleTestCase()
            );
            $this->pairRepository->removeAllPairsInTestCase($newTestCase);
        }

        return $testCases;
    }

    private function createSingleTestCase()
    {
        $pairWithHighestRating = $this->pairRepository->getPairWithHighestRating();

        $firstParameterIndex = $this->getParameterIndexForGivenValueIndex($pairWithHighestRating[0]);
        $secondParameterIndex = $this->getParameterIndexForGivenValueIndex($pairWithHighestRating[1]);

        $testCase = $this->createEmptyTestCase();
        $testCase[$firstParameterIndex] = $pairWithHighestRating[0];
        $testCase[$secondParameterIndex] = $pairWithHighestRating[1];

        $parameterIndexOrdering = $this->createRandomParameterIndexOrderingWithGivenLeadingIndices([
            $firstParameterIndex, $secondParameterIndex
        ]);

        for ($parameterIndex = 2; $parameterIndex < $this->dataset->getTotalNumberOfParameters(); ++$parameterIndex) {
            $parameterIndexInTestCase = $parameterIndexOrdering[$parameterIndex];
            $testCase[$parameterIndexInTestCase] = $this->findLeastUsedParameterValueForGivenTestCase($testCase, $parameterIndexOrdering, $parameterIndex);
        }

        return $testCase;
    }

    private function findLeastUsedParameterValueForGivenTestCase($testCase, $parameterIndexOrdering, $parameterIndex)
    {
        $parameterIndexInTestCase = $parameterIndexOrdering[$parameterIndex];
        $possibleValueIndices = $this->dataset->getValueIndices()[$parameterIndexInTestCase];

        $highestUsedCount = PHP_INT_MAX;
        $bestPossibleValueIndex = null;

        for ($possibleValueIndex = 0; $possibleValueIndex < count($possibleValueIndices); ++$possibleValueIndex) {
            $currentUsedCount = 0;
            for ($alreadyUsedParameterIndex = 0; $alreadyUsedParameterIndex < $parameterIndex; ++$alreadyUsedParameterIndex) {
                $candidate = [$possibleValueIndices[$possibleValueIndex], $testCase[$parameterIndexOrdering[$alreadyUsedParameterIndex]]];
                if ($this->pairRepository->isPairUsed($candidate)) {
                    ++$currentUsedCount;
                }
            }
            if ($currentUsedCount < $highestUsedCount) {
                $highestUsedCount = $currentUsedCount;
                $bestPossibleValueIndex = $possibleValueIndex;
            }
        }

        return $possibleValueIndices[$bestPossibleValueIndex];
    }

    private function translateTestCaseToOriginalValues($testCase)
    {
        return collect($testCase)
            ->map(function ($valueIndex) {
                return $this->dataset->getValues()[$valueIndex];
            })->all();
    }

    private function getParameterIndexForGivenValueIndex($valueIndex)
    {
        return $this->dataset->getParameterIndexMappings()[$valueIndex];
    }

    private function createEmptyTestCase()
    {
        return array_fill(0, $this->dataset->getTotalNumberOfParameters(), null);
    }

    private function createRandomParameterIndexOrderingWithGivenLeadingIndices($leadingIndexes)
    {
        $remainingParametersIndexes = collect(
            range(0, $this->dataset->getTotalNumberOfParameters() - 1)
        )->filter(function ($index) use ($leadingIndexes) {
            return !in_array($index, $leadingIndexes);
        })->shuffle(1);

        return [...$leadingIndexes, ...$remainingParametersIndexes];
    }
}
