<?php namespace Scheduler\Shifts;

use Illuminate\Support\ServiceProvider;
use Scheduler\Shifts\Entity\Shift;
use Scheduler\Shifts\Contracts\Shift as ShiftInterface;

/**
 * Class ShiftsServiceProvider
 * @package app\Shifts
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftsServiceProvider extends  ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ShiftInterface::class, Shift::class);
    }
}