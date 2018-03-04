<?php

namespace MNHN\PortailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use MNHN\PortailBundle\Entity\P_Program;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Partners
 *
 * @ORM\Table(name="partners")
 * @ORM\Entity(repositoryClass="MNHN\PortailBundle\Repository\PartnersRepository")
 * @Vich\Uploadable
 */
class Partners
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="partners_image", fileNameProperty="imageName", size="imageSize")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpeg", "image/png", "image/jpg"},
     *     mimeTypesMessage = "Mauvais format. Chargez un format JPG ou PNG valide."
     * )
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
     * @ORM\Column(type="datetime", nullable = true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Program", inversedBy="partners")
     * @ORM\JoinColumn(name="program", referencedColumnName="id", nullable=true)
     */
    private $program;

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
     * Set programs
     *
     * @param \MNHN\PortailBundle\Entity\P_Program $program
     *
     * @return P_Program
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
}

