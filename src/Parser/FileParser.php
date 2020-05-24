<?php

namespace Richenzi\Pairwise\Parser;

use Richenzi\Pairwise\Exceptions\FileNotFound;

class FileParser extends InputParser
{
    /**
     * {@inheritDoc}
     */
    public function parse($path, $options = [])
    {
        $options = array_merge($this->getDefaultOptions(), $options);

        if (!file_exists($path)) {
            throw new FileNotFound($path);
        }

        $values = collect(file($path))
            ->map(function ($line) use ($options) {
                return $this->parseLine($line, $options);
            })->toArray();

        return parent::parse($values, $options);
    }

    private function parseLine($line, $options)
    {
        return preg_split('/' . $options['delimiter'] . '/', trim($line));
    }

    private function getDefaultOptions()
    {
        return [
            'delimiter' => '\t'
        ];
    }
}
