<?php namespace Scheduler\Http\Controllers;

use Carbon\Carbon;
use League\Fractal\Manager;
use Scheduler\Shifts\Commands\AssignShift;
use Scheduler\Shifts\Commands\CreateShift;
use Scheduler\Shifts\Commands\UpdateShift;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Requests\ListShiftsRequest;
use Scheduler\Shifts\Requests\StoreShiftRequest;
use Scheduler\Shifts\Requests\UpdateShiftRequest;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Users\Contracts\User;

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

    /**
     * @param Shift $shift
     * @param UpdateShiftRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Shift $shift, UpdateShiftRequest $request)
    {
        $shift = $this->dispatchFrom(UpdateShift::class, $request, ['shift' => $shift]);
        return $this->respondWithItem($shift, $this->shiftTransformer);
    }

    /**
     * @param Shift $shift
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUser(Shift $shift, User $user)
    {
        //Check that user has permission to edit this resource
//        $this->authorizeUser($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('entity'), 'edit', 'shifts');

        $shift = $this->dispatch(new AssignShift($shift, $user));

        return $this->respondWithItem($shift, $this->shiftTransformer, ['manager','employee']);
    }
}