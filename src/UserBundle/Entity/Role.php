<?php
namespace UserBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="symtest.role")
 * @ORM\Entity()
 *
 * @author Russell
 */
class Role implements RoleInterface 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="name", type="string", length=30)
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     * @var string
     */
    private $role;
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     * @ORM\JoinTable(name="symtest.user_role")
     * @var ArrayCollection
     */
    private $users;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add users
     *
     * @param \UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UserBundle\Entity\User $users
     */
    public function removeUser(\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
