<?php

namespace App\Domain\Vehicle;

class Fleet
{
    private int $id;
    private string $name;

    /**
     * @param int $id
     *
     * @return Fleet
     */
    public function setId (int $id): Fleet
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return Fleet
     */
    public function setName (string $name): Fleet
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getId (): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName (): string
    {
        return $this->name;
    }
}
