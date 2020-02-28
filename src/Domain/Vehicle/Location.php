<?php

namespace App\Domain\Vehicle;

use App\Infra\Interfaces\Comparable;

class Location
{
    private int $id;
    private float $latitude;
    private float $longitude;

    /**
     * @return int
     */
    public function getId (): int
    {
        return $this->id;
    }

    /**
     * @param float $latitude
     *
     * @return Location
     */
    public function setLatitude (float $latitude): Location
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude (): float
    {
        return $this->latitude;
    }

    /**
     * @param float $longitude
     *
     * @return Location
     */
    public function setLongitude (float $longitude): Location
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude (): float
    {
        return $this->longitude;
    }

    /**
     * @param int $id
     */
    public function setId (int $id): void
    {
        $this->id = $id;
    }
}
