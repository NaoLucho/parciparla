<?php

namespace Builder\ListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Menu
 *
 * @ORM\Table(name="g_list")
 * @ORM\Entity(repositoryClass="Builder\ListBundle\Repository\G_ListRepository")
 * #Gedmo\Loggable
 */
class G_List
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
     * #Gedmo\Versioned
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * #Gedmo\Versioned
     */
    private $title;

    /**
    * @ORM\OneToMany(targetEntity="Builder\ListBundle\Entity\G_ListItem", mappedBy="list", cascade={"persist"}, orphanRemoval=true)
    * @ORM\OrderBy({"order" = "ASC"})
    */
    protected $listItems;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked = false;
    
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
     * @return Menu
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
        $this->listItems = new ArrayCollection();
    }

    public function getListItems()
    {
        return $this->listItems;
    }

    /**
     * Set list item
     *
     * @param string $listItems
     *
     * @return List Item
     */
    public function setListItems($listItems)
    {
        $this->listItems = [];
        foreach ($listItems as $i) {
            $this->addListItem($i);
        }

        return $this;
    }

    /**
     * Add listItem
     *
     * @param \Builder\ListBundle\Entity\G_ListItem $listItem
     *
     * @return G_List
     */
    public function addListItem(\Builder\ListBundle\Entity\G_ListItem $listItems)
    {
        $listItems->setList($this);
        $this->listItems[] = $listItems;

        return $this;
    }

    /**
     * Remove listItem
     *
     * @param \Builder\ListBundle\Entity\G_ListItem $listItem
     */
    public function removeListItem(\Builder\ListBundle\Entity\G_ListItem $listItem)
    {
        $this->listItems->removeElement($listItem);
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return G_List
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
     * Set locked
     *
     * @param boolean $locked
     *
     * @return Content
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }
}
