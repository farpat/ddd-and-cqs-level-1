<?php

namespace Tests;


use App\Application\Command\ParkVehicleCommand;
use App\Application\Command\RegisterVehicleCommand;
use App\Application\Handler\ParkVehicleHandler;
use App\Application\Handler\RegisterVehicleHandler;
use App\Domain\Vehicle\Fleet;
use App\Domain\Vehicle\Vehicle;
use PHPUnit\Framework\TestCase;

class ParkVehicleTest extends TestCase
{
    use DatabaseTestCase;

    private Fleet $fleet;
    private Vehicle $vehicle;

    protected function setUp (): void
    {
        $this->setUpDatabase();

        $this->fleet = $this->makeFleet(['name' => 'my fleet']);
        $this->vehicle = $this->makeVehicle(['name' => 'a vehicle']);
        $this->assertCount(1, $this->repository->read('fleets'));
        $this->assertCount(1, $this->repository->read('vehicles'));

        $registerCommand = new RegisterVehicleCommand($this->fleet, $this->vehicle);
        (new RegisterVehicleHandler($this->repository))->handle($registerCommand);

        $registrations = $this->repository->read("fleets_vehicles");
        $this->assertCount(1, $registrations);
        $this->assertEquals($this->fleet->getId(), $registrations[0]['fleet_id']);
    }

    /** @test */
    public function successfully_park_a_vehicle ()
    {
        //given
        $location = $this->makeLocation(['latitude' => 0, 'longitude' => 0]);
        $this->assertCount(1, $this->repository->read('locations'));

        //when
        $parkCommand = new ParkVehicleCommand($this->vehicle, $location);
        (new ParkVehicleHandler($this->repository))->handle($parkCommand);

        //then
        $locationInDatabase = $this->repository->read("locations", ['id' => 1]);
        $this->assertEquals(0, $locationInDatabase['latitude']);
        $this->assertEquals(0, $locationInDatabase['longitude']);
    }

    /** @test */
    public function cant_localize_my_vehicle_to_the_same_location_two_times_in_a_row ()
    {
        //given
        $location = $this->makeLocation(['latitude' => 0, 'longitude' => 0]);
        $this->assertCount(1, $this->repository->read('locations'));

        //when
        $parkCommand = new ParkVehicleCommand($this->vehicle, $location);
        (new ParkVehicleHandler($this->repository))->handle($parkCommand);

        //then
        $this->expectException(\Exception::class);
        (new ParkVehicleHandler($this->repository))->handle($parkCommand);
    }
}