<?php namespace Scheduler\Http\Controllers;

use Carbon\Carbon;
use League\Fractal\Manager;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Requests\ListShiftsRequest;
use Scheduler\Shifts\Requests\StoreShiftRequest;
use Scheduler\Shifts\Transformer\ShiftTransformer;

/**
 * Class ShiftsController
 * @package Scheduler\Http\Controllers
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftsController extends Controller
{
    /**
     * @var ShiftRepository
     */
    protected $shiftRepository;

    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @var ShiftTransformer
     */
    protected $shiftTransformer;

    /**
     * ShiftsController constructor.
     * @param Manager $fractal
     * @param ShiftRepository $shiftRepository
     * @param ShiftTransformer $shiftTransformer
     */
    public function __construct(Manager $fractal, ShiftRepository $shiftRepository, ShiftTransformer $shiftTransformer)
    {
        $this->shiftRepository = $shiftRepository;
        $this->fractal = $fractal;
        $this->shiftTransformer = $shiftTransformer;
    }

    /**
     * @param ListShiftsRequest $request
     * @return mixed
     */
    public function index(ListShiftsRequest $request)
    {
        //Retrieve shifts between in time period
        $shifts = $this->shiftRepository->getShiftsBetween(Carbon::parse($request->get('startDateTime')), Carbon::parse($request->get('endDateTime')));
        return $this->respondWithCollection($shifts, $this->shiftTransformer, ['manager', 'employee']);
    }

    /**
     * @param StoreShiftRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreShiftRequest $request)
    {
        $shift = $this->dispatchFrom(CreateShift::class, $request);
        return $this->respondWithItem($shift, $this->shiftTransformer, ['manager', 'employee']);
    }
}