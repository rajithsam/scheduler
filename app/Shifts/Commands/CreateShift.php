<?php namespace Scheduler\Shifts\Commands;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Bus\SelfHandling;
use Scheduler\Jobs\Job;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class CreateShift
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class CreateShift extends Job implements SelfHandling
{
    /**
     * @var int
     */
    public $manager_id;

    /**
     * @var int|null
     */
    public $employee_id;

    /**
     * @var float
     */
    public $break;

    /**
     * @var string
     */
    public $start_time;

    /**
     * @var string
     */
    public $end_time;

    /**
     * CreateShift constructor.
     * @param $manager_id
     * @param $employee_id
     * @param $break
     * @param $start_time
     * @param $end_time
     */
    public function __construct($break, $start_time, $end_time, $employee_id = null, $manager_id = null)
    {
        $this->manager_id = $manager_id;
        $this->employee_id = $employee_id;
        $this->break = $break;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    /**
     * @param UserRepository $userRepository
     * @param ShiftRepository $shiftRepository
     * @param Shift $shift
     * @param Guard $auth
     * @return Shift
     */
    public function handle(UserRepository $userRepository, ShiftRepository $shiftRepository, Shift $shift, Guard $auth)
    {
        $shift->setBreak($this->break);
        $shift->setStartTime($this->start_time);
        $shift->setEndTime($this->end_time);

        if ($this->employee_id) {
            $shift->setEmployee($userRepository->getOneById($this->employee_id));
        }

        if ($this->manager_id) {
            $shift->setManager($userRepository->getOneById($this->manager_id));
        } else {
            $shift->setManager($auth->getUser());
        }

        $shiftRepository->store($shift);
        $shiftRepository->update($shift);

        return $shift;
    }
}