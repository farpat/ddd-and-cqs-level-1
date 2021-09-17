<?php

namespace App\Domain\Vehicle;

class Vehicle
{
    private int $id;
    private string $name;
    private ?Location $location;

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

    public function getLocation (): ?Location
    {
        return $this->location;
    }

    public function setId (int $id): Vehicle
    {
        $this->id = $id;
        return $this;
    }

    public function setName (string $name): Vehicle
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Location|null $location
     *
     * @return Vehicle
     */
    public function setLocation (?Location $location): Vehicle
    {
        $this->location = $location;
        return $this;
    }
}
