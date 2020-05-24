<?php

namespace Richenzi\Pairwise;

class Dataset
{
    /**
     * The parameters parsed from the input.
     *
     * @var Parameter[]
     */
    private $parameters;

    /**
     * Flattened values of all parameters.
     * E.g. for input ['number' => ['one', 'two', 'three'], 'color' => ['blue', 'green'], 'vegetable' => ['carrot', 'potato']],
     * values will be ['one', 'two', 'three', 'blue', 'green', 'carrot', 'potato']
     *
     * @var array
     */
    private $values;

    /**
     * Parameter values with their indices across all values instead of original values.
     * E.g. for input ['number' => ['one', 'two', 'three'], 'color' => ['blue', 'green'], 'vegetable' => ['carrot', 'potato']],
     * valueIndices will be [[0,1,2],[3,4],[5,6]]
     *
     * @var array
     */
    private $valueIndices;

    /**
     * Flattened values of all parameters with their parameter index instead of original value.
     * E.g. for input ['number' => ['one', 'two', 'three'], 'color' => ['blue', 'green'], 'vegetable' => ['carrot', 'potato']],
     * parameterIndexMappings will be [0,0,0,1,1,2,2]
     *
     * @var array
     */
    private $parameterIndexMappings;


    public function __construct($parameters)
    {
        $this->parameters = $parameters;
        $this->init();
    }

    private function init()
    {
        $this->flattenAllValuesIntoOneArray();
        $this->createValueIndices();
        $this->createParameterIndexMappings();
    }

    private function flattenAllValuesIntoOneArray()
    {
        $this->values = collect($this->parameters)
            ->map(fn($parameter) => $parameter->getValues())
            ->flatten(1)
            ->toArray();
    }

    private function createValueIndices()
    {
        $index = 0;
        $this->valueIndices = [];
        foreach ($this->parameters as $parameter) {
            $this->valueIndices[] = $this->getParameterValuesIndicesStartingFromGivenIndex($parameter, $index);
        }
    }

    private function getParameterValuesIndicesStartingFromGivenIndex($parameter, &$index)
    {
        return range($index, ($index += count($parameter->getValues())) - 1);
    }

    private function createParameterIndexMappings()
    {
        foreach ($this->parameters as $index => $parameter) {
            for ($i = 0; $i < count($parameter->getValues()); ++$i) {
                $this->parameterIndexMappings[] = $index;
            }
        }
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getValueIndices()
    {
        return $this->valueIndices;
    }

    public function getParameterIndexMappings()
    {
        return $this->parameterIndexMappings;
    }

    public function getTotalNumberOfParameters()
    {
        return count($this->parameters);
    }

    public function getTotalNumberOfValues()
    {
        return count($this->values);
    }
}
