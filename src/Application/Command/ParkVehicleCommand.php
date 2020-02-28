<?php

namespace App\Application\Command;

use App\Domain\Vehicle\Location;
use App\Domain\Vehicle\Vehicle;

class ParkVehicleCommand
{
    private Vehicle $vehicleToPark;
    private Location $location;

    public function __construct (Vehicle $vehicleToPark, Location $location)
    {
        $this->vehicleToPark = $vehicleToPark;
        $this->location = $location;
    }

    /**
     * @return Vehicle
     */
    public function getVehicleToPark (): Vehicle
    {
        return $this->vehicleToPark;
    }

    /**
     * @return Location
     */
    public function getLocation (): Location
    {
        return $this->location;
    }
}
