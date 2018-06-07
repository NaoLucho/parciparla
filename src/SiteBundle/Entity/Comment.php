<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Sonata\UserBundle\Entity\User;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
// use Vich\UploaderBundle\Mapping\Annotation as Vich;
// use Symfony\Component\HttpFoundation\File\File;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\CommentRepository")
 * ____Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Article
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
     * @ORM\Column(name="authorname", type="string", length=255, nullable=true)
     */
    private $authorName;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", nullable=true)
     * @ORM\JoinColumn(name="owner", referencedColumnName="id", onDelete="SET NULL")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="publishedat", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    public function __construct(User $user = null)
    {
        $this->author = $user;
        //$this->rights = new ArrayCollection();
        $this->localisations = new ArrayCollection();
        $this->isActive = false;
        $this->publishedAt = new \DateTime();
    }
}