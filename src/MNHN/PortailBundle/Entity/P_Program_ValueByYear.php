<?php

namespace MNHN\PortailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Menu
 *
 * @ORM\Table(name="p_program_valuebyyear")
 * @ORM\Entity(repositoryClass="MNHN\AdminBundle\Repository\F_FieldRepository")
 */
class P_Program_ValueByYear
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    //Année
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    //Value (nombre)
    /**
     * @var integer
     *
     * @ORM\Column(name="nb", type="integer")
     * @Assert\NotBlank(groups={"default"})
     */
    private $nb;


    /**
    * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Program", inversedBy="nbSubscriberByYear")
    * @ORM\JoinColumn(name="program_id_subscriber", referencedColumnName="id", nullable=true)
    */
    private $program_nbSubscriber;

    /**
    * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Program", inversedBy="nbActiveUserByYear")
    * @ORM\JoinColumn(name="program_id_activeuser", referencedColumnName="id", nullable=true)
    */
    private $program_nbActiveUser;

    /**
    * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Program", inversedBy="nbCollectedDataByYear")
    * @ORM\JoinColumn(name="program_id_collecteddata", referencedColumnName="id", nullable=true)
    */
    private $program_nbCollectedData;

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
     * Set year
     *
     * @param integer $year
     *
     * @return P_Program_ValueByYear
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set nb
     *
     * @param integer $nb
     *
     * @return P_Program_ValueByYear
     */
    public function setNb($nb)
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * Get nb
     *
     * @return integer
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * Set programNbSubscriber
     *
     * @param \MNHN\PortailBundle\Entity\P_Program $programNbSubscriber
     *
     * @return P_Program_ValueByYear
     */
    public function setProgramNbSubscriber(\MNHN\PortailBundle\Entity\P_Program $programNbSubscriber = null)
    {
        $this->program_nbSubscriber = $programNbSubscriber;

        return $this;
    }

    /**
     * Get programNbSubscriber
     *
     * @return \MNHN\PortailBundle\Entity\P_Program
     */
    public function getProgramNbSubscriber()
    {
        return $this->program_nbSubscriber;
    }

    /**
     * Set programNbActiveUser
     *
     * @param \MNHN\PortailBundle\Entity\P_Program $programNbActiveUser
     *
     * @return P_Program_ValueByYear
     */
    public function setProgramNbActiveUser(\MNHN\PortailBundle\Entity\P_Program $programNbActiveUser = null)
    {
        $this->program_nbActiveUser = $programNbActiveUser;

        return $this;
    }

    /**
     * Get programNbActiveUser
     *
     * @return \MNHN\PortailBundle\Entity\P_Program
     */
    public function getProgramNbActiveUser()
    {
        return $this->program_nbActiveUser;
    }

    /**
     * Set programNbCollectedData
     *
     * @param \MNHN\PortailBundle\Entity\P_Program $programNbCollectedData
     *
     * @return P_Program_ValueByYear
     */
    public function setProgramNbCollectedData(\MNHN\PortailBundle\Entity\P_Program $programNbCollectedData = null)
    {
        $this->program_nbCollectedData = $programNbCollectedData;

        return $this;
    }

    /**
     * Get programNbCollectedData
     *
     * @return \MNHN\PortailBundle\Entity\P_Program
     */
    public function getProgramNbCollectedData()
    {
        return $this->program_nbCollectedData;
    }
}
