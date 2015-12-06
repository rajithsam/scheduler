<?php namespace Scheduler\Shifts\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;

/**
 * Class UpdateShift
 * @package Scheduler\Shifts\Commands
 * @author Sam Tape <sctape@gmail.com>
 */
class UpdateShift implements SelfHandling
{
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
     * @var Shift
     */
    private $shift;

    /**
     * UpdateShift constructor.
     * @param Shift $shift
     * @param $break
     * @param $start_time
     * @param $end_time
     */
    public function __construct(Shift $shift, $break, $start_time, $end_time)
    {
        $this->break = $break;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->shift = $shift;
    }

    /**
     * @param ShiftRepository $shiftRepository
     * @return Shift
     */
    public function handle(ShiftRepository $shiftRepository)
    {
        $this->shift->setBreak($this->break);
        $this->shift->setStartTime($this->start_time);
        $this->shift->setEndTime($this->end_time);

        $shiftRepository->update($this->shift);

        return $this->shift;
    }
}