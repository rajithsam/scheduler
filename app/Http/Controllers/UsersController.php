<?php namespace Scheduler\Http\Controllers;

use League\Fractal\Manager;
use Scheduler\Users\Contracts\User;
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
     * UsersController constructor.
     * @param Manager $fractal
     * @param UserTransformer $userTransformer
     */
    public function __construct(Manager $fractal, UserTransformer $userTransformer)
    {
        $this->fractal = $fractal;
        $this->userTransformer = $userTransformer;
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
}