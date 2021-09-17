<?php

namespace App\Application\Handler;

use App\Application\Command\ParkVehicleCommand;
use App\Infra\Repository\Repository;
use Exception;

class ParkVehicleHandler
{
    private Repository $repository;

    public function __construct (Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function handle (ParkVehicleCommand $command)
    {
        $locationInArray = $this->repository->read('vehicles', ['id' => $command->getVehicleToPark()->getId()]);

        if ((int)$locationInArray['location_id'] === $command->getLocation()->getId()) {
            throw new Exception("The vehicle << {$command->getVehicleToPark()->getName()} >> is already parked at this location");
        }

        $this->repository->update('vehicles', $command->getVehicleToPark()->getId(), [
            'location_id' => $command->getLocation()->getId()
        ]);
    }
}
