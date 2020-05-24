<?php

namespace Richenzi\Pairwise\Tests;

use PHPUnit\Framework\TestCase;
use Richenzi\Pairwise\Dataset;
use Richenzi\Pairwise\PairUtils;
use Richenzi\Pairwise\Parameter;

class PairUtilsTest extends TestCase
{
    /** @test */
    public function it_creates_pairs_from_arrays()
    {
        $result = PairUtils::createPairsFromArray(['a', 'b', 'c']);

        $this->assertCount(3, $result);
        $this->assertEquals(
            [['a', 'b'], ['a', 'c'], ['b', 'c']],
            $result
        );
    }
}
