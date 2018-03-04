<?php

namespace MNHN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Menu
 *
 * @ORM\Table(name="f_formfield")
 * @ORM\Entity(repositoryClass="MNHN\AdminBundle\Repository\F_FormFieldRepository")
 */
class F_FormField
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
    * @ORM\ManyToOne(targetEntity="MNHN\AdminBundle\Entity\F_Form", inversedBy="formFields")
    * @ORM\JoinColumn(name="form_id", referencedColumnName="id", nullable=false)
    */
    protected $form;

    /**
    * @ORM\ManyToOne(targetEntity="MNHN\AdminBundle\Entity\F_Field", inversedBy="formFields")
    * @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=false)
    */
    protected $field;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="mandatory", type="boolean", nullable=true)
     */
    private $mandatory;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    /**
     * @var string
     *
     * @ORM\Column(name="info", type="string", length=1024, nullable=true)
     */
    private $info;
    

    /**
    * @ORM\OneToMany(targetEntity="MNHN\AdminBundle\Entity\F_FormFieldRights", mappedBy="formField", cascade={"persist"}, orphanRemoval=true)
    */
    protected $formfieldrights;

    // /**
    //  * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group", cascade={"persist"})
    //  * @ORM\JoinTable(name="f_formfield_rights")
    //  */
    // private $rights;
    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="mode", type="string", length=255)
    //  */
    // private $mode;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formfieldrights = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set mandatory
     *
     * @param boolean $mandatory
     *
     * @return F_FormField
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Get mandatory
     *
     * @return boolean
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return F_FormField
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set form
     *
     * @param \MNHN\AdminBundle\Entity\F_Form $form
     *
     * @return F_FormField
     */
    public function setForm(\MNHN\AdminBundle\Entity\F_Form $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return \MNHN\AdminBundle\Entity\F_Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set field
     *
     * @param \MNHN\AdminBundle\Entity\F_Field $field
     *
     * @return F_FormField
     */
    public function setField(\MNHN\AdminBundle\Entity\F_Field $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \MNHN\AdminBundle\Entity\F_Field
     */
    public function getField()
    {
        return $this->field;
    }


    /**
     * Add formfieldright
     *
     * @param \MNHN\AdminBundle\Entity\F_FormFieldRights $formfieldright
     *
     * @return F_FormField
     */
    public function addFormfieldright(\MNHN\AdminBundle\Entity\F_FormFieldRights $formfieldright)
    {
        $formfieldright->setFormField($this);
        $this->formfieldrights[] = $formfieldright;

        return $this;
    }

    /**
     * Remove formfieldright
     *
     * @param \MNHN\AdminBundle\Entity\F_FormFieldRights $formfieldright
     */
    public function removeFormfieldright(\MNHN\AdminBundle\Entity\F_FormFieldRights $formfieldright)
    {
        $this->formfieldrights->removeElement($formfieldright);
    }

    /**
     * Get formfieldrights
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormfieldrights()
    {
        return $this->formfieldrights;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return F_FormField
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}
