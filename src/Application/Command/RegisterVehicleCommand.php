<?php

namespace App\Application\Command;

use App\Domain\Vehicle\Fleet;
use App\Domain\Vehicle\Vehicle;

class RegisterVehicleCommand
{
    private Fleet $fleet;
    private Vehicle $vehicleToRegister;

    public function __construct (Fleet $fleet, Vehicle $vehicleToRegister)
    {
        $this->fleet = $fleet;
        $this->vehicleToRegister = $vehicleToRegister;
    }

    /**
     * @return Fleet
     */
    public function getFleet (): Fleet
    {
        return $this->fleet;
    }

    /**
     * @return Vehicle
     */
    public function getVehicleToRegister (): Vehicle
    {
        return $this->vehicleToRegister;
    }
}
