<?php

namespace Tests;

use App\Domain\Vehicle\Fleet;
use App\Domain\Vehicle\Location;
use App\Domain\Vehicle\Vehicle;
use App\Infra\Repository\Repository;
use PDO;

trait DatabaseTestCase
{
    protected Repository $repository;
    protected ?PDO $pdo = null;

    public function makeFleet ($data): Fleet
    {
        $data = $this->repository->create('fleets', $data);

        $fleet = new Fleet;
        $fleet->setId((int)$data['id']);
        $fleet->setName($data['name']);

        return $fleet;
    }

    public function makeVehicle ($data): Vehicle
    {
        $data = $this->repository->create('vehicles', $data);

        $vehicle = new Vehicle;
        $vehicle->setId((int)$data['id']);
        $vehicle->setName((string)$data['name']);

        if (isset($data['location_id'])) {
            $locationInArray = $this->repository->read('locations', ['id' => $data['location_id']]);
            $location = new Location;
            $location->setId((int)$locationInArray['id']);
            $location->setLatitude((float)$locationInArray['latitude']);
            $location->setLongitude((float)$locationInArray['longitude']);
            $vehicle->setLocation($location);
        }

        return $vehicle;
    }

    public function makeLocation ($data): Location
    {
        $data = $this->repository->create('locations', $data);

        $location = new Location;
        $location->setId((int)$data['id']);
        $location->setLatitude((float)$data['latitude']);
        $location->setLongitude((float)$data['longitude']);

        return $location;
    }

    protected function setUpDatabase (): void
    {
        parent::setUp();

        if ($this->pdo === null) {
            $this->repository = new Repository($this->pdo = new PDO('sqlite::memory:'));

            $this->pdo->exec(<<<SQL
CREATE table locations (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   latitude DOUBLE(11,8) NOT NULL,
   longitude DOUBLE(11,8) NOT NULL
);

CREATE table fleets (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   name VARCHAR(191) NOT NULL
);

CREATE table vehicles (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   name VARCHAR(191) NOT NULL,
   location_id INT(11) DEFAULT NULL,
    
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

CREATE table fleets_vehicles (
   fleet_id INT(11) NOT NULL,
   vehicle_id INT(11) NOT NULL,
   
   FOREIGN KEY (fleet_id) REFERENCES fleets(id),
   FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);
SQL
            );
        }

        $this->pdo->beginTransaction();
    }

    protected function tearDown (): void
    {
        $this->pdo->rollBack();
    }
}