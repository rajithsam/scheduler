<?php namespace Scheduler\Shifts\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Contracts\User;

/**
 * Class AssignShift
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class AssignShift implements SelfHandling
{
    /**
     * @var Shift
     */
    private $shift;

    /**
     * @var User
     */
    private $user;

    /**
     * AssignShift constructor.
     * @param Shift $shift
     * @param User $user
     */
    public function __construct(Shift $shift, User $user)
    {
        $this->shift = $shift;
        $this->user = $user;
    }

    /**
     * @param ShiftRepository $shiftRepository
     * @return Shift
     */
    public function handle(ShiftRepository $shiftRepository)
    {
        $this->shift->setEmployee($this->user);
        $shiftRepository->update($this->shift);

        return $this->shift;
    }
}