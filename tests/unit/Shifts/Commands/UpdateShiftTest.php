<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Shifts\Commands\UpdateShift;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;

/**
 * Class UpdateShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShiftTest extends Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Test that the command has the correct fields
     */
    public function testClassHasCorrectAttributes()
    {
        $start_time = date_create('now')->format('r');
        $end_time = date_create('+1 hour')->format('r');
        $shift = mockery::mock(Shift::class);

        $command = new UpdateShift($shift, 20, $start_time, $end_time);
        $this->assertEquals($shift, $command->shift);
        $this->assertEquals(20, $command->break);
        $this->assertEquals($start_time, $command->start_time);
        $this->assertEquals($end_time, $command->end_time);
        $this->assertInstanceOf(UpdateShift::class, $command);
    }

    /**
     * Test that the handle method finds the shift and updates the time fields
     */
    public function testHandle()
    {
        $shift = mockery::mock(Shift::class);
        $command = new UpdateShift($shift, 20, date_create('now')->format('r'), date_create('+1 hour')->format('r'));

        $shift->shouldReceive('setBreak')->once()->with($command->break)->andReturnNull();
        $shift->shouldReceive('setStartTime')->once()->with($command->start_time)->andReturnNull();
        $shift->shouldReceive('setEndTime')->once()->with($command->end_time)->andReturnNull();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('update')->once()->with($shift)->andReturnNull();

        $this->assertInstanceOf(Shift::class, $command->handle($shiftRepository));
    }
}