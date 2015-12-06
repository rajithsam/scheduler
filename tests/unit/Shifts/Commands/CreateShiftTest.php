<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Illuminate\Contracts\Auth\Guard;
use Mockery;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class CreateShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class CreateShiftTest extends Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testClassHasCorrectAttributes()
    {
        $start_time = date_create('now')->format('r');
        $end_time = date_create('+1 hour')->format('r');

        $command = new CreateShift(15, $start_time, $end_time, 2, 1);
        $this->assertEquals(1, $command->manager_id);
        $this->assertEquals(2, $command->employee_id);
        $this->assertEquals(15, $command->break);
        $this->assertEquals($start_time, $command->start_time);
        $this->assertEquals($end_time, $command->end_time);
    }

    public function testHandle()
    {
        $command = new CreateShift(1, 2, 15, date_create('now')->format('r'), date_create('+1 hour')->format('r'));

        $manager = mockery::mock(User::class);
        $employee = mockery::mock(User::class);

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setBreak')->once()->with($command->break)->andReturnNull();
        $shift->shouldReceive('setEmployee')->once()->with($employee)->andReturnNull();
        $shift->shouldReceive('setManager')->once()->with($manager)->andReturnNull();
        $shift->shouldReceive('setStartTime')->once()->with($command->start_time)->andReturnNull();
        $shift->shouldReceive('setEndTime')->once()->with($command->end_time)->andReturnNull();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('store')->once()->with($shift)->andReturnNull();
        $shiftRepository->shouldReceive('update')->once()->with($shift)->andReturnNull();

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneById')->once()->with($command->employee_id)->andReturn($employee);
        $userRepository->shouldReceive('getOneById')->once()->with($command->manager_id)->andReturn($manager);

        $auth = mockery::mock(Guard::class);

        $this->assertInstanceOf(Shift::class, $command->handle($userRepository, $shiftRepository, $shift, $auth));
    }
}