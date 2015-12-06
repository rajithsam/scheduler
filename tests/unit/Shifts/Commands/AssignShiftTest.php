<?php namespace Scheduler\Codeception\Unit\Shifts\Commands;

use Codeception\TestCase\Test;
use Illuminate\Contracts\Bus\SelfHandling;
use Mockery;
use Scheduler\Shifts\Commands\AssignShift;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Contracts\User;

/**
 * Class AssignShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShiftTest extends Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     *
     */
    public function testClassHasCorrectAttributes()
    {
        $shift = mockery::mock(Shift::class);
        $user = mockery::mock(User::class);

        $command = new AssignShift($shift, $user);
        $this->assertInstanceOf(AssignShift::class, $command);
        $this->assertInstanceOf(SelfHandling::class, $command);
    }

    /**
     * Test that the command is handled correctly
     */
    public function testHandle()
    {
        $user = mockery::mock(User::class);
        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setEmployee')->once()->with($user)->andReturnNull();

        $command = new AssignShift($shift, $user);

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('update')->once()->with($shift)->andReturnNull();

        $this->assertInstanceOf(Shift::class, $command->handle($shiftRepository));
    }
}