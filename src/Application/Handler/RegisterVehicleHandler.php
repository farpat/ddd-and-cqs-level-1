<?php

namespace App\Application\Handler;

use App\Application\Command\RegisterVehicleCommand;
use App\Infra\Repository\Repository;
use Exception;

class RegisterVehicleHandler
{
    /**
     * @var Repository
     */
    private Repository $repository;

    public function __construct (Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function handle (RegisterVehicleCommand $command)
    {
        $registration = $this->repository->read('fleets_vehicles', [
            'fleet_id'   => $command->getFleet()->getId(),
            'vehicle_id' => $command->getVehicleToRegister()->getId()
        ]);

        if (!empty($registration)) {
            throw new Exception("The vehicle << {$command->getVehicleToRegister()->getName()} >> has already been registered into the fleet << {$command->getFleet()->getName()} >>");
        }

        $this->repository->create('fleets_vehicles', [
            'fleet_id'   => $command->getFleet()->getId(),
            'vehicle_id' => $command->getVehicleToRegister()->getId()
        ]);
    }
}
