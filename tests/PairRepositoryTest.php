<?php

namespace Richenzi\Pairwise\Tests;

use PHPUnit\Framework\TestCase;
use Richenzi\Pairwise\Dataset;
use Richenzi\Pairwise\PairRepository;
use Richenzi\Pairwise\Parameter;

class PairRepositoryTest extends TestCase
{
    private $pairRepository;

    protected function setUp(): void
    {
        $dataset = new Dataset([
            new Parameter('number', ['one', 'two', 'three']),
            new Parameter('color', ['blue', 'green'])
        ]);

        $this->pairRepository = new PairRepository($dataset);
    }

    /** @test */
    public function it_creates_all_pairs_of_value_indices_when_constructed()
    {
        $this->assertEquals([[0, 3], [0, 4], [1, 3], [1, 4], [2, 3], [2, 4]], $this->pairRepository->getPairs());
    }

    /** @test */
    public function it_marks_every_pair_as_unused_when_constructed()
    {
        collect($this->pairRepository->getPairs())
            ->each(function ($pair) {
                $this->assertFalse($this->pairRepository->isPairUsed($pair));
            });
    }

    /** @test */
    public function it_increments_each_pair_rating_when_constructed()
    {
        collect($this->pairRepository->getPairs())
            ->each(function ($pair) {
                $this->assertEquals(5, $this->pairRepository->getPairRating($pair));
            });
    }

    /** @test */
    public function it_returns_total_number_of_pairs()
    {
        $this->assertEquals(6, $this->pairRepository->getTotalNumberOfPairs());
    }

    /** @test */
    public function it_can_remove_pair()
    {
        $this->pairRepository->removePair([0, 3]);

        $this->assertEquals(5, $this->pairRepository->getTotalNumberOfPairs());
    }

    /** @test */
    public function it_can_remove_all_pairs_in_test_case()
    {
        $this->pairRepository->removeAllPairsInTestCase([0, 3]);

        $this->assertEquals(5, $this->pairRepository->getTotalNumberOfPairs());
    }

    /** @test */
    public function it_decrements_pair_rating_when_pair_is_removed()
    {
        $this->pairRepository->removePair($pair = [0, 3]);

        $this->assertEquals(3, $this->pairRepository->getPairRating($pair));
    }

    /** @test */
    public function it_marks_pair_as_used_when_pair_is_removed()
    {
        $this->pairRepository->removePair($pair = [0, 3]);

        $this->assertTrue($this->pairRepository->isPairUsed($pair));
    }
}
