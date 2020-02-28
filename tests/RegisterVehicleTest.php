<?php

namespace Tests;


use App\Application\Command\RegisterVehicleCommand;
use App\Application\Handler\RegisterVehicleHandler;
use PHPUnit\Framework\TestCase;

class RegisterVehicleTest extends TestCase
{
    use DatabaseTestCase;

    protected function setUp (): void
    {
        $this->setUpDatabase();
    }

    /** @test */
    public function i_can_register_a_vehicle ()
    {
        //given
        $fleet = $this->makeFleet(['name' => 'my fleet']);
        $vehicle = $this->makeVehicle(['name' => 'a vehicle']);
        $this->assertCount(1, $this->repository->read('fleets'));
        $this->assertCount(1, $this->repository->read('vehicles'));

        //when
        $registerCommand = new RegisterVehicleCommand($fleet, $vehicle);
        (new RegisterVehicleHandler($this->repository))->handle($registerCommand);

        //then
        $registrations = $this->repository->read("fleets_vehicles");
        $this->assertCount(1, $registrations);
        $this->assertEquals($fleet->getId(), $registrations[0]['fleet_id']);
    }

    /** @test */
    public function i_cant_register_same_vehicle_twice ()
    {
        //given
        $fleet = $this->makeFleet(['name' => 'my fleet']);
        $vehicle = $this->makeVehicle(['name' => 'a vehicle']);
        $this->assertCount(1, $this->repository->read('fleets'));
        $this->assertCount(1, $this->repository->read('vehicles'));

        //when
        $registerCommand = new RegisterVehicleCommand($fleet, $vehicle);
        (new RegisterVehicleHandler($this->repository))->handle($registerCommand);

        //then
        $this->expectException(\Exception::class);
        (new RegisterVehicleHandler($this->repository))->handle($registerCommand);
    }

    /** @test */
    public function same_vehicle_can_belong_to_more_than_one_fleet ()
    {
        //given
        $fleet1 = $this->makeFleet(['name' => 'my fleet']);
        $fleet2 = $this->makeFleet(['name' => 'the fleet of another user']);
        $vehicle = $this->makeVehicle(['name' => 'a vehicle']);
        $this->assertCount(2, $this->repository->read('fleets'));
        $this->assertCount(1, $this->repository->read('vehicles'));

        $registerCommand = new RegisterVehicleCommand($fleet1, $vehicle);
        (new RegisterVehicleHandler($this->repository))->handle($registerCommand);

        //when
        $registerCommand = new RegisterVehicleCommand($fleet2, $vehicle);
        (new RegisterVehicleHandler($this->repository))->handle($registerCommand);


        //then
        $registrations = $this->repository->read("fleets_vehicles");
        $this->assertCount(2, $registrations);

    }
}