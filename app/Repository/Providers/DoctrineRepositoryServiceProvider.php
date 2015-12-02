<?php namespace Scheduler\Repository\Providers;

use Doctrine\ORM\EntityManager;
use Illuminate\Support\ServiceProvider;
use Scheduler\Repository\Contracts\DoctrineRepository;
use Scheduler\Shifts\Entity\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Entity\User;
use Scheduler\Users\Repository\UserRepository;

/**
 * Class DoctrineRepositoryServiceProvider
 * @package Scheduler\Repository\Providers
 * @author Sam Tape <sctape@gmail.com>
 */
class DoctrineRepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected static $entityRepositoryMap = [
        User::class => UserRepository::class,
        Shift::class => ShiftRepository::class,
    ];

    /**
     * @param EntityManager $entityManager
     */
    public function boot(EntityManager $entityManager)
    {
        foreach (self::$entityRepositoryMap as $entityClassName => $repoClassName) {

            $this->app->singleton(
                $repoClassName,
                function () use ($entityClassName, $entityManager) {
                    /**
                     * @var DoctrineRepository $repo
                     */
                    $repo = $entityManager->getRepository($entityClassName);
                    return $repo;
                }
            );
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}