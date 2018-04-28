<?php

namespace MNHN\PortailBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * P_Structure
 *
 * @ORM\Table(name="p_structure")
 * @ORM\Entity(repositoryClass="MNHN\PortailBundle\Repository\P_StructureRepository")
 * @Vich\Uploadable
 */
class P_Structure
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_structure_type", referencedColumnName="id", nullable=true)
     * //G_LIST: structureType
     */
    private $structureType;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="ownedStructures")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id", onDelete="SET NULL")
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="structures", cascade={"persist"})
     */
    private $members;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", nullable=true)
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "Le code postal n'est pas valide. Il doit contenir {{ limit }} caractères"
     * )
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true))
     */
    private $website;

    // /**
    //  * @ORM\ManyToOne(targetEntity="Csf\CoreBundle\Entity\Attachment")
    //  * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", nullable=true)
    //  */
    // private $logo;

    // /**
    //  * @var array
    //  *
    //  * @ORM\Column(name="competences", type="array")
    //  */
    // private $competences;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="structure_logo_image", fileNameProperty="imageName", size="imageSize")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isPorteur", type="boolean")
     */
    private $isPorteur;

    /**
     * @var bool
     *
     * @ORM\Column(name="isRelai", type="boolean")
     */
    private $isRelai;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCollectifCoordo", type="boolean")
     */
    private $isCollectifCoordo;


    /**
     * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\P_Program", cascade={"persist"}, mappedBy="structureAnim")
     */
    private $programs;


    public function __construct(User $user = null)
    {
        $this->isActive = false;
        $this->isPorteur = false;
        $this->isRelai = false;
        $this->isCollectifCoordo = false;
        $this->owner = $user;
        $this->members = new ArrayCollection();
    }

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
     * @return P_Structure
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
     * Gets the value of structureType.
     *
     * @return $structureType
     */
    public function getStructureType()
    {
        return $this->structureType;
    }

    /**
     * Sets the value of structureType.
     *
     * @param $structureType the structure type
     *
     * @return self
     */
    public function setStructureType($structureType)
    {
        $this->structureType = $structureType;

        return $this;
    }

    /**
     * Gets the value of owner.
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Sets the value of owner.
     *
     * @param User $owner
     *
     * @return self
     */
    public function setOwner(User $owner)
    {
        if ($owner == null) {
            $this->owner = null;
        } else {
            $this->owner = $owner;
        }

        return $this;
    }

    /**
     * @param User $members
     */
    public function addMembers(User $members)
    {
        $this->members[] = $members;
    }

    /**
     * @param User $members
     */
    public function removeMembers(User $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return P_Structure
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return P_Structure
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return P_Structure
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return P_Structure
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Gets the value of latitude.
     *
     * @return $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the value of latitude.
     *
     * @param $latitude the structure type
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Gets the value of longitude.
     *
     * @return $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the value of longitude.
     *
     * @param $longitude the structure type
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }


    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return P_Structure
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return P_Structure
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return P_Structure
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return P_Structure
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return P_Structure
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Image
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param integer $imageSize
     *
     * @return Image
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * 
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isPorteur
     *
     * @param boolean $isPorteur
     *
     * @return P_Structure
     */
    public function setIsPorteur($isPorteur)
    {
        $this->isPorteur = $isPorteur;

        return $this;
    }

    /**
     * Get isPorteur
     *
     * @return bool
     */
    public function getIsPorteur()
    {
        return $this->isPorteur;
    }

    /**
     * Set isRelai
     *
     * @param boolean $isRelai
     *
     * @return P_Structure
     */
    public function setIsRelai($isRelai)
    {
        $this->isRelai = $isRelai;

        return $this;
    }

    /**
     * Get isRelai
     *
     * @return bool
     */
    public function getIsRelai()
    {
        return $this->isRelai;
    }

    /**
     * Set isCollectifCoordo
     *
     * @param boolean $isCollectifCoordo
     *
     * @return P_Structure
     */
    public function setIsCollectifCoordo($isCollectifCoordo)
    {
        $this->isCollectifCoordo = $isCollectifCoordo;

        return $this;
    }

    /**
     * Get isCollectifCoordo
     *
     * @return bool
     */
    public function getIsCollectifCoordo()
    {
        return $this->isCollectifCoordo;
    }

    /**
     * @author LW
     *
     * @param P_Program $program
     */
    public function addProgram(P_Program $program)
    {
        $this->programs[] = $program;
    }

    /**
     * @author LW
     *
     * @param P_Program $program
     */
    public function removeProgram(P_Program $program)
    {
        $this->programs->removeElement($program);
    }

    /**
     * Set programs
     *
     * @param boolean $programs
     *
     * @return P_Program
     */
    public function setPrograms(P_Program $programs)
    {
        $this->programs = $programs;

        return $this;
    }

    /**
     * @author LW
     * @return mixed
     */
    public function getPrograms()
    {
        return $this->programs;
    }

    /**
     * Add member
     *
     * @param \Application\Sonata\UserBundle\Entity\User $member
     *
     * @return P_Structure
     */
    public function addMember(\Application\Sonata\UserBundle\Entity\User $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \Application\Sonata\UserBundle\Entity\User $member
     */
    public function removeMember(\Application\Sonata\UserBundle\Entity\User $member)
    {
        $this->members->removeElement($member);
    }
}
