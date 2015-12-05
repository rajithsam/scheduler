<?php namespace Scheduler\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\HoursTransformer;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Repository\UserRepository;
use Scheduler\Users\Transformer\UserTransformer;

/**
 * Class UsersController
 * @package Scheduler\Http\Controllers
 * @author Sam Tape <sctape@gmail.com>
 */
class UsersController extends Controller
{
    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @var UserTransformer
     */
    protected $userTransformer;

    /**
     * @var ShiftRepository
     */
    protected $shiftRepository;

    /**
     * @var ShiftTransformer
     */
    protected $shiftTransformer;

    /**
     * UsersController constructor.
     * @param Manager $fractal
     * @param UserTransformer $userTransformer
     * @param ShiftRepository $shiftRepository
     * @param ShiftTransformer $shiftTransformer
     */
    public function __construct(Manager $fractal, UserTransformer $userTransformer, ShiftRepository $shiftRepository, ShiftTransformer $shiftTransformer)
    {
        $this->fractal = $fractal;
        $this->userTransformer = $userTransformer;
        $this->shiftRepository = $shiftRepository;
        $this->shiftTransformer = $shiftTransformer;
    }

    /**
     * GET /users/{user}
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->respondWithItem($user, $this->userTransformer);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listShifts(User $user, Request $request)
    {
        //Don't allow employees to view other employee's shifts
        //todo: figure out if managers can access all employees' shifts
//        if ($input['id'] != $input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('id')) {
//            throw new UserNotAuthorized;
//        }

        //Get shifts
        $shifts = $this->shiftRepository->getByEmployee($user);
        return $this->respondWithCollection($shifts, $this->shiftTransformer, $request->get('include'));
    }

    /**
     * @param User $user
     * @param Request $request
     * @param HoursTransformer $hoursTransformer
     * @return \Illuminate\Http\JsonResponse
     */
    public function listHours(User $user, Request $request, HoursTransformer $hoursTransformer)
    {
        //Make sure requested user matches auth user
//        //todo: figure out if managers can access all employees' hours
//        if ($input['id'] != $input[AuthHandler::TOKEN_ATTRIBUTE]->getMetaData('id')) {
//            throw new UserNotAuthorized;
//        }

        //Get hours and transform to more readable collection
        $hours = $this->shiftRepository->getHoursCountGroupedByWeekFor($user);
        return $this->respondWithCollection($hours, $hoursTransformer);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param UserRepository $userRepository
     * @return mixed
     */
    public function listCoworkers(User $user, Request $request, UserRepository $userRepository)
    {
        //Check that the auth user matches the requested user
        //todo: determine if manager's should have access
//        if ($input[AuthHandler::TOKEN_ATTRIBUTE]->getMetadata('id') != $input['id']) {
//            throw new UserNotAuthorized;
//        }

        $shifts = $this->shiftRepository->getByEmployee($user);

        //Loop over shifts getting employees that work at the same time for each shift
        foreach($shifts as $shift) {
            $coworkers = $userRepository->getEmployeesWorkingBetween($shift->getStartTime(), $shift->getEndTime(), [$user]);
            $shift->setCoworkers($coworkers);
        }

        return $this->respondWithCollection($shifts, $this->shiftTransformer, 'coworkers');
    }
}