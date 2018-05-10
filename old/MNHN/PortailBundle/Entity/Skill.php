<?php

namespace MNHN\PortailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Skills
 *
 * @ORM\Table(name="p_skills")
 * @ORM\Entity(repositoryClass="MNHN\PortailBundle\Repository\SkillRepository")
 */
class Skill
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="skills", cascade={"persist"})
     */
    private $users;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Skills
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

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @param User $users
     */
    public function addUsers(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param User $users
     */
    public function removeUsers(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }
}

