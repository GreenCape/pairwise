<?php

namespace Richenzi\Pairwise;

class PairRepository
{
    /**
     * Holds an instance of the dataset.
     *
     * @var Dataset
     */
    private Dataset $dataset;

    /**
     * All pairs generated from parameter values or more precisely from their indices.
     * @see Dataset::$valueIndices
     *
     * @var array
     */
    private $pairs;

    /**
     * 2D lookup array to find out whether given pair [a,b] was already used or not.
     * For pair [a,b] to be considered "unused", unusedPairLookup[a][b] must equal to 1.
     *
     * @var array[]
     */
    private $unusedPairLookup;

    /**
     * Rating of given value is equal to number of its occurrences in all generated pairs.
     * E.g. having two pairs (1,2) and (1,3), rating of 1 is 2, and rating of 2 and 3 is 1
     * Rating of value drops by one when pair with given value is used in the test case and pair removed from all pairs.
     * @see PairRepository::$pairs
     *
     * @var array
     */
    private $valueRatings;


    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
        $this->init();
    }

    /**
     * Returns true when all pairs have been removed.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getTotalNumberOfPairs() == 0;
    }

    /**
     * Returns total number of pairs.
     */
    public function getTotalNumberOfPairs()
    {
        return count($this->pairs);
    }

    /**
     * Get all pairs we have.
     */
    public function getPairs()
    {
        return $this->pairs;
    }

    /**
     * Returns true if pair was already used.
     *
     * @param array $pair
     * @return bool
     */
    public function isPairUsed($pair)
    {
        return $this->unusedPairLookup[$pair[0]][$pair[1]] == 0 && $this->unusedPairLookup[$pair[1]][$pair[0]] == 0;
    }

    /**
     * Marks given pair as unused.
     *
     * @param array $pair
     */
    public function markPairAsUnused($pair)
    {
        $this->unusedPairLookup[$pair[0]][$pair[1]] = 1;
    }

    /**
     * Marks given pair as used.
     *
     * @param array $pair
     */
    public function markPairAsUsed($pair)
    {
        $this->unusedPairLookup[$pair[0]][$pair[1]] = 0;
    }

    /**
     * For every pair of values (or more precisely their indices) generated from given
     * test case we - decrement rating of both values
     *              - mark pair as used
     *              - remove pair from
     *
     * @param array $testCase
     */
    public function removeAllPairsInTestCase($testCase)
    {
        collect(PairUtils::createPairsFromArray($testCase))->each(function ($pair) {
            $this->removePair($pair);
        });
    }

    /**
     * Removes given pair.
     *
     * @param array $pairToRemove
     */
    public function removePair($pairToRemove)
    {
        foreach ($this->pairs as $index => $pair) {
            if ($pair[0] == $pairToRemove[0] && $pair[1] == $pairToRemove[1]) {
                $this->decrementPairRating($pairToRemove);
                $this->markPairAsUsed($pairToRemove);
                unset($this->pairs[$index]);
                return;
            }
        }
    }

    /**
     * Returns pair with the highest rating, where rating
     * is equal to sum of its values` ratings
     *
     * @return mixed
     */
    public function getPairWithHighestRating()
    {
        return collect($this->pairs)
            ->sortByDesc(function ($pair) {
                return $this->getPairRating($pair);
            })->first();
    }

    /**
     * Returns pair rating as the sum of ratings of both values.
     *
     * @param array $pair
     * @return mixed
     */
    public function getPairRating($pair)
    {
        return $this->valueRatings[$pair[0]] + $this->valueRatings[$pair[1]];
    }

    private function init()
    {
        $this->initUnusedPairLookup();
        $this->initValueRatings();
        $this->createPairs();
    }

    private function initUnusedPairLookup()
    {
        $this->unusedPairLookup = array_fill(
            0,
            $this->dataset->getTotalNumberOfValues(),
            array_fill(0, $this->dataset->getTotalNumberOfValues(), 0)
        );
    }

    private function initValueRatings()
    {
        $this->valueRatings = array_fill(0, $this->dataset->getTotalNumberOfValues(), 0);
    }

    private function createPairs()
    {
        for ($firstParameterIndex = 0; $firstParameterIndex < $this->dataset->getTotalNumberOfParameters() - 1; ++$firstParameterIndex) {
            $firstParameterValueIndices = $this->dataset->getValueIndices()[$firstParameterIndex];
            for ($secondParameterIndex = $firstParameterIndex + 1; $secondParameterIndex < $this->dataset->getTotalNumberOfParameters(); ++$secondParameterIndex) {
                $secondParameterValueIndices = $this->dataset->getValueIndices()[$secondParameterIndex];
                foreach ($firstParameterValueIndices as $firstParameterValueIndex) {
                    foreach ($secondParameterValueIndices as $secondParameterValueIndex) {
                        $this->pairs[] = ($pair = [$firstParameterValueIndex, $secondParameterValueIndex]);
                        $this->markPairAsUnused($pair);
                        $this->incrementPairRating($pair);
                    }
                }
            }
        }
    }

    private function incrementPairRating($pair, $increment = 1)
    {
        $this->valueRatings[$pair[0]] += $increment;
        $this->valueRatings[$pair[1]] += $increment;
    }

    private function decrementPairRating($pair, $decrement = 1)
    {
        $this->incrementPairRating($pair, -$decrement);
    }
}
