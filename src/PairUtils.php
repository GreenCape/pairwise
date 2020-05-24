<?php

namespace Richenzi\Pairwise;

class PairUtils
{
    /**
     * Creates pairs from elements of the array.
     *
     * @param $array
     * @return array
     */
    public static function createPairsFromArray($array)
    {
        $pairs = [];

        for ($i = 0; $i < count($array) - 1; ++$i) {
            for ($j = $i + 1; $j < count($array); ++$j) {
                $pairs[] = [$array[$i], $array[$j]];
            }
        }

        return $pairs;
    }
}
