<?php namespace Scheduler\Repository\Providers;

use Doctrine\ORM\EntityManagerInterface;
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
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->app->make(EntityManagerInterface::class);

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
}