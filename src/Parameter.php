<?php

namespace Richenzi\Pairwise;

class Parameter
{
    /**
     * The name of given parameter.
     *
     * @var string
     */
    private $name;

    /**
     * The values of given parameter.
     *
     * @var array
     */
    private $values;


    public function __construct($name, $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
