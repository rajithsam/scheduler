<?php namespace Scheduler\Users\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Users\Contracts\User as UserInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Scheduler\Users\Entity
 * @author Sam Tape <sctape@gmail.com>
 *
 * @ORM\Entity(repositoryClass="Scheduler\Users\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface, AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Timestamps;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $token;

    /**
     * @var ArrayCollection|Shift[]
     * @ORM\OneToMany(targetEntity="Scheduler\Shifts\Entity\Shift", mappedBy="manager")
     */
    protected $managed_shifts;

    /**
     * @var ArrayCollection|Shift[]
     * @ORM\OneToMany(targetEntity="Scheduler\Shifts\Entity\Shift", mappedBy="employee")
     */
    protected $employed_shifts;

    /**
     * Initialize collections
     */
    public function __construct()
    {
        $this->managed_shifts = new ArrayCollection;
        $this->employed_shifts = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * The type of caller
     *
     * @return string
     */
    public function getCallerType()
    {
        return 'users';
    }

    /**
     * The unique ID to identify the caller with
     *
     * @return int
     */
    public function getCallerId()
    {
        return $this->getId();
    }

    /**
     * The caller's roles
     *
     * @return array
     */
    public function getCallerRoles()
    {
        return [$this->getRole()];
    }
}