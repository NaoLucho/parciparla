<?php

namespace MNHN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Menu
 *
 * @ORM\Table(name="f_form")
 * @ORM\Entity(repositoryClass="MNHN\AdminBundle\Repository\F_FormRepository")
 */
class F_Form
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255)
     */
    private $entity;

    /**
    * @ORM\OneToMany(targetEntity="MNHN\AdminBundle\Entity\F_FormField", mappedBy="form", cascade={"persist"})
    * @ORM\OrderBy({"position" = "ASC"})
    */
    protected $formFields;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formFields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set entity
     *
     * @param string $entity
     *
     * @return F_Field
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return F_Form
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
     * Set title
     *
     * @param string $title
     *
     * @return F_Form
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add formField
     *
     * @param \MNHN\AdminBundle\Entity\F_FormField $formField
     *
     * @return F_Form
     */
    public function addFormField(\MNHN\AdminBundle\Entity\F_FormField $formField)
    {
        $this->formFields[] = $formField;

        return $this;
    }

    /**
     * Remove formField
     *
     * @param \MNHN\AdminBundle\Entity\F_FormField $formField
     */
    public function removeFormField(\MNHN\AdminBundle\Entity\F_FormField $formField)
    {
        $this->formFields->removeElement($formField);
    }

    /**
     * Get formFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormFields()
    {
        return $this->formFields;
    }
}
