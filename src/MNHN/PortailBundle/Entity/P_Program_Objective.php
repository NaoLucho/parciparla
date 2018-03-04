<?php

namespace MNHN\PortailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table(name="p_program_objective")
 * @ORM\Entity(repositoryClass="MNHN\AdminBundle\Repository\P_Program_ObjectiveRepository")
 */
class P_Program_Objective
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
    * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Program", inversedBy="program_objectives")
    * @ORM\JoinColumn(name="program_id", referencedColumnName="id", nullable=false)
    */
    protected $program;
    
    /**
    * @ORM\ManyToOne(targetEntity="MNHN\AdminBundle\Entity\G_ListItem")
    * @ORM\JoinColumn(name="objective_id", referencedColumnName="id", nullable=false)
    */ //G_List: networkObs
    protected $objective;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="integer")
     * @Assert\GreaterThan(0)
     * @Assert\LessThan(4)
     */
    private $priority;
    

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
     * Set priority
     *
     * @param integer $priority
     *
     * @return P_Program_Objective
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set program
     *
     * @param \MNHN\PortailBundle\Entity\P_Program $program
     *
     * @return P_Program_Objective
     */
    public function setProgram(\MNHN\PortailBundle\Entity\P_Program $program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \MNHN\PortailBundle\Entity\P_Program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set objective
     *
     * @param \MNHN\AdminBundle\Entity\G_ListItem $objective
     *
     * @return P_Program_Objective
     */
    public function setObjective(\MNHN\AdminBundle\Entity\G_ListItem $objective)
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * Get objective
     *
     * @return \MNHN\AdminBundle\Entity\G_ListItem
     */
    public function getObjective()
    {
        return $this->objective;
    }
}
