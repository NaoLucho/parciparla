<?php
namespace MNHN\PortailBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * P_Program
 *
 * @ORM\Table(name="p_program")
 * @ORM\Entity(repositoryClass="MNHN\PortailBundle\Repository\P_ProgramRepository")
 * @Vich\Uploadable
 */
class P_Program
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    //BLOCK CARACTERISTIQUES:
    /** Nom de l’observatoire : 
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(groups={"default"})
     */
    private $name;

    /** Logo
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="program_logo_image", fileNameProperty="logoName", size="logoSize")
     * 
     * @var File
     * ___Assert\NotBlank(groups={"program_step1"})
     * @Assert\File(
     *   maxSize = "5M",
     *   groups={"program_step1"}
     * )
     */
    private $logoFile;
    
    /** Logo
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $logoName;

    /** Logo
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $logoSize;

    /** Photo
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="program_photo_image", fileNameProperty="photoName", size="photoSize")
     * 
     * @var File
     * ___Assert\NotBlank(groups={"program_step1"})
     */
    private $photoFile;
    
    /** Photo
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $photoName;

    /** Photo
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $photoSize;

    /** Lien vidéo (URL)
     * @var string
     *
     * @ORM\Column(name="video_link", type="string", length=1024, nullable=true)
     */
    private $videoLink;

    /** Votre observatoire est-il ?
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_status", referencedColumnName="id" ) //nullable=false
     * ___Assert\NotBlank(groups={"program_step1"})
     * ___Assert\Valid(groups={"program_step1"})
     */ //G_List: program_status
    private $status;
    /** OLD VERSION WITH SPECIFIC TABLE:
     * ___ORM\ManyToOne(targetEntity="Csf\CoreBundle\Entity\P_ProgramStatus")
     * ___ORM\JoinColumn(name="program_status", referencedColumnName="id" ) //nullable=false
     */
    //private $programStatus;

    /** Description résumée de l’observatoire
     * @var string
     *
     * @ORM\Column(name="description", type="text" ) //nullable=false
     * ___Assert\NotBlank(groups={"program_step1"})
     * ___Assert\Length(max=1000, groups={"program_step1"})
     */
    private $description; //MAXSIZE 1000

    
    //Objectif(s) de l’observatoire. Indication: Classer de 1 à 3 afin de prioriser les objectifs de votre programme:
    /**
    * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\P_Program_Objective", mappedBy="program", cascade={"persist"})
    * @ORM\OrderBy({"priority" = "ASC"})
    * @Assert\Valid()
    */
    protected $objectives;
    
    // /**
    //  * @ORM\ManyToMany(targetEntity="BuilderBundle\Entity\G_ListItem", cascade={"persist"})
    //  * @ORM\JoinTable(name="p_program_objectives1")
    //  */ //G_List: program_objectives
    // private $objectives1; //Obligatoire (1 min)

    // /** Thème(s) abordé(s)
    //  * @ORM\ManyToMany(targetEntity="BuilderBundle\Entity\G_ListItem", cascade={"persist"})
    //  * @ORM\JoinTable(name="p_program_objectives2")
    //  */ //G_List: program_objectives
    // private $objectives2; //Obligatoire (1 min)

    // /**
    //  * @ORM\ManyToMany(targetEntity="BuilderBundle\Entity\G_ListItem", cascade={"persist"})
    //  * @ORM\JoinTable(name="p_program_objectives3")
    //  */ //G_List: program_objectives
    // private $objectives3; //Obligatoire (1 min)

    /** Thème(s) abordé(s)
     * @ORM\ManyToMany(targetEntity="BuilderBundle\Entity\G_ListItem", cascade={"persist"})
     * @ORM\JoinTable(name="p_program_themes")
     * ___Assert\Count(min=1, groups={"program_step1"})
     */ //G_List: themes
    private $themes; //Obligatoire (1 min)

    /** Groupe(s) étudié(s) 
     * @ORM\ManyToMany(targetEntity="BuilderBundle\Entity\G_ListItem", cascade={"persist"})
     * @ORM\JoinTable(name="p_program_taxons")
     * ___Assert\Count(min=1, groups={"program_step1"})
     */ //G_List: taxons
    private $taxons; //Obligatoire (1 min) //Checklist avec parent: A VOIR SI ON LE FAIT DANS G_ListItem:parent
    
    /** Accessibilité de l’observatoire
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_accessibility", referencedColumnName="id" ) //nullable=false
     * ___Assert\NotBlank(groups={"program_step1"})
     */ //G_List: program_accessibility
    private $accessibility; //Obligatoire
    
    /** Saisonnalité
     * @ORM\ManyToMany(targetEntity="BuilderBundle\Entity\G_ListItem", cascade={"persist"})
     * @ORM\JoinTable(name="p_program_seasons")
     * ___Assert\Count(min=1, groups={"program_step1"})
     */ //G_List: seasons
    private $seasons; //Obligatoire (1 min)

    // Matériel(s) nécessaire(s) pour l’observation
    /**
     * @var string
     *
     * @ORM\Column(name="stuff", type="text" ) //nullable=false
     */
    private $stuff;
    
    /** Racontez une anecdote sur votre observatoire
     * @var string
     *
     * @ORM\Column(name="anecdote", type="string", length=500, nullable=true)
     */
    private $anecdote;

    //BLOCK CONTACT:
    /** Site internet (lien)
     * @var string
     *
     * @ORM\Column(name="website_URL", type="string", length=512, nullable=true)
     */
    private $websiteURL;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_URL", type="string", length=512, nullable=true)
     * ___Assert\Length(max=200, groups={"program_step3"})
     */
    private $facebookURL;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_URL", type="string", length=512, nullable=true)
     * ___Assert\Length(max=200, groups={"program_step3"})
     */
    private $twitterURL;

    /**
     * @var string
     * @ORM\Column(name="contact_email", type="string", length=255, nullable=true) //nullable=false
     * @Assert\Email(groups={"program_step3"})
     */
    private $contactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_phone", type="string", length=255, nullable=true)
     * @Assert\Regex(pattern="/^\(?(0|(\+|00)[0-9]{2})\)? ?[1-9]([-. ]?[0-9]{2}){4}$/",
     *  message="Numéro non valide,
     *  format demandé: 0112345678/+3312345678/003312345678 et espaces acceptés.",
     *  groups={"program_step3"})
     */
    private $contactPhone;


    //Structure en charge de l’animation de l’observatoire (TODO: une seule)
    /**
     * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Structure", inversedBy="programs")
     * @ORM\JoinColumn(name="structure_anim", referencedColumnName="id" ) //nullable=false
     * ___Assert\NotBlank(groups={"program_step3"})
     */
    private $structureAnim; //INITIALEMENT l'une des structures gérées par celui qui crée l'observatoire
    
    //Personne en charge de l'animation de l'observatoire 
    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="programsAnimated")
     * @ORM\JoinTable(name="p_program_user_owner_anim")
     */
    private $ownersAnim; //Obligatoire (1 min) //INITIALEMENT add celui qui crée l'observatoire


    //BLOCK RESEAU

    //Structure responsable scientifique
    /**
     * @ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Structure")
     * @ORM\JoinColumn(name="structure_resp_scientist")
     */
    private $structureRespScientist;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_contact_resp_scientist", referencedColumnName="id", nullable=true)
     */
    private $contactRespScientist;


    // réseaux d’observation 
    // /**
    //  * @__ORM\ManyToOne(targetEntity="MNHN\PortailBundle\Entity\P_Structure", inversedBy="programsRespScientist")
    //  * @__ORM\JoinColumn(name="structure_network_obs")
    //  */
    /**
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_network_obs", referencedColumnName="id", nullable=true)
     */ //G_List: networkObs
    private $networkObs;


    // Relais locaux (structure relai) //MULTIPLE
    /**
     * @ORM\ManyToMany(targetEntity="MNHN\PortailBundle\Entity\P_Structure")
     * @ORM\JoinTable(name="p_program_structure_relay_local")
     */
    private $relayLocal;

    //Logos des partenaires – Plusieurs logos possibles
    //TODO


    //BLOCK RESULTATS: BEX
    //Champs texte + liens ouvrant des PDF
    /** RESULTATS
     * @var string
     *
     * @ORM\Column(name="results", type="text", nullable=true) //nullable=false
     */
    private $results;

    //ADD LIENS OUVRANT DES PDF

    //BLOCK AUTRES INFORMATIONS:
    //Année de lancement du programme
    /**
     * @var \DateTime
     * @ORM\Column(name="start_op_year", type="integer", nullable=true)
     */
    private $startOpYear;

    //Durée du programme
    /**
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_program_duration", referencedColumnName="id" ) //nullable=false
     * ___Assert\NotBlank(groups={"program_step4"})
     */ //G_List: program_duration
    private $programDuration;

    //BLOCK EVALUATION

    // Le nombre de personnes inscrites au programme
    /**
    * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\P_Program_ValueByYear", mappedBy="program_nbSubscriber", cascade={"persist"})
    * @ORM\OrderBy({"year" = "ASC"})
    * @Assert\Valid(groups={"default"})
    */
    protected $nbSubscriberByYear; //many

    // Le nombre de participants actifs
    /**
    * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\P_Program_ValueByYear", mappedBy="program_nbActiveUser", cascade={"persist"})
    * @ORM\OrderBy({"year" = "ASC"})
    */
    protected $nbActiveUserByYear; //many

    // Le nombre de données élémentaires collectées
    /**
    * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\P_Program_ValueByYear", mappedBy="program_nbCollectedData", cascade={"persist"})
    * @ORM\OrderBy({"year" = "ASC"})
    */
    protected $nbCollectedDataByYear; //many

    /** Autres indicateurs pour évaluer le programme
     * @var string
     *
     * @ORM\Column(name="others_indicators", type="string", length=512, nullable=true)
     */
    private $othersIndicators; //OUI/NON Si OUI Préciser (texte)


    /** En moyenne, combien d’années participent un observateur ?
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_suscriber_duration_moy", referencedColumnName="id", nullable=true)
     */ //G_List: suscriberDuration => < 1 an/1 à 3 ans/> 3 ans
    private $suscriberDurationMoy;

    /** Evolution générale du programme par rapport à l’année précédente (qualitatif)
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_program_quality_evolution", referencedColumnName="id", nullable=true)
     */ //G_List: programQualityEvolution => Croissance/Décroissance/Stabilité
    private $programQualityEvolution;
    
    //BLOCK PROTOTCOLE

    /** Qui valide les données ?
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_program_data_validator", referencedColumnName="id", nullable=true)
     */ //G_List: programDataAnalists => structure animatrice du projet/les participants/experts valideurs/partenaire scientifique
    private $programDataValidator;

    /** Qui analyse les données ?
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_program_data_analyser", referencedColumnName="id", nullable=true)
     */ //G_List: programDataAnalists => structure animatrice du projet/les participants/experts valideurs/partenaire scientifique
    private $programDataAnalyser;


    //BLOCK DONNEES

    /** Sous quelle forme sont restituées les données ? 
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_program_data_format", referencedColumnName="id", nullable=true)
     */ //G_List: programDataFormat => Brutes/Synthèses
    private $programDataFormat;

    /** Des retours sont-ils prévus vers les contributeurs ? 
     * @var string
     *
     * @ORM\Column(name="data_feedback", type="string", length=512, nullable=true)
     */
    private $dataFeedback; //OUI/NON Si OUI Préciser le lien URL vers les résultats

    /** Où sont stockées les données ? 
     * @var string
     *
     * @ORM\Column(name="data_storage_localisation", type="string", length=512, nullable=true)
     */
    private $dataStorageLocalisation; 
    //base de données interne au projet OU base de donnés régionale OU autre (texte)

    /** Le programme s’inscrit-il dans le protocole SINP ?
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_protocol_sinp", referencedColumnName="id", nullable=true)
     */ //G_List: protocolSINP => tout/en partie/non
    private $protocolSINP;


    //BLOCK RESSOURCES FINANCIERES
    /** Nombre d’ETP 
     * @var int
     *
     * @ORM\Column(name="nb_etp", type="integer", nullable=true)
     */
    private $nbETP;

    /** Nombre de personnes travaillant sur le programme
     * @var int
     *
     * @ORM\Column(name="nb_working_persons", type="integer", nullable=true)
     */
    private $nbWorkingPersons;

    /** Budget annuel 
     * @ORM\ManyToOne(targetEntity="BuilderBundle\Entity\G_ListItem")
     * @ORM\JoinColumn(name="li_program_annual_budget", referencedColumnName="id", nullable=true)
     */ //G_List: annualBudget => De 1 000 à 5 000€/De 5 000 à 10 000€/De 10 000 à 50 000€/De 50 000 à 100 000€/Plus de 100 000€
    private $annualBudget;


    /** Partenaires financiers
     * @var string
     *
     * @ORM\Column(name="financePartners", type="string", length=512, nullable=true)
     */
    private $financePartners; 
    //Nom des partenaires financiers du projet et si possible information sur la répartition financière (Privé ….% ; Public : …..% ; ONG :…….%)

    //BLOCK LOCALISATION: 
    //Portée géographique de l’observatoire
    /**
     * @ORM\ManyToMany(targetEntity="MNHN\PortailBundle\Entity\Geom", cascade={"persist"})
     * @ORM\JoinTable(name="p_geom_localisation")
     * __Assert\Count(min=1, groups={"program_step2"})
     */
    private $localisations;

    /**
     * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\Partners", mappedBy="program", cascade={"persist", "remove"}, orphanRemoval= true)
     * @Assert\Valid(groups={"program_step3"})
     */
    private $partners;

    /**
     * @ORM\OneToMany(targetEntity="MNHN\PortailBundle\Entity\Program_Results", mappedBy="program", cascade={"persist"})
     * @Assert\Valid(groups={"program_step3"})
     */
    private $resultsPdf;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @var bool
     *
     * @ORM\Column(name="isFinalizedForm", type="boolean")
     */
    private $isFinalizedForm;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActiveProgram", type="boolean")
     */
    private $isActiveProgram;


    public function __construct()
    {
        $this->objectives = new ArrayCollection();
        // $this->objectives1 = new ArrayCollection();
        // $this->objectives2 = new ArrayCollection();
        // $this->objectives3 = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        //$this->structuresRespScientist = new ArrayCollection();
        $this->taxons = new ArrayCollection();

        $this->nbSubscriberByYear = new ArrayCollection();
        $this->nbActiveUserByYear = new ArrayCollection();
        $this->nbCollectedDataByYear = new ArrayCollection();

        $this->ownersAnim = new ArrayCollection();
        $this->localisations = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->resultsPdf = new ArrayCollection();
        //TODO: ADD GET/ADD/REMOVE OF THEM

        $this->isFinalizedForm = false;
        $this->isActiveProgram = false;
        
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
     * @return P_Program
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
    public function setLogoFile(File $image = null)
    {
        if ($image) {
            $this->logoFile = $image;
        }

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
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param string $logoName
     *
     * @return P_Program
     */
    public function setLogoName($logoName)
    {
        if($logoName) {

            $this->logoName = $logoName;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogoName()
    {
        return $this->logoName;
    }

    /**
     * @param integer $logoSize
     *
     * @return Image
     */
    public function setLogoSize($logoSize)
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getLogoSize()
    {
        return $this->logoSize;
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
    public function setPhotoFile(File $image = null)
    {
        $this->photoFile = $image;

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
    public function getPhotoFile()
    {
        return $this->photoFile;
    }


    /**
     * Set photoName
     *
     * @param string $photoName
     *
     * @return P_Program
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;

        return $this;
    }

    /**
     * Get photoName
     *
     * @return string
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * Set photoSize
     *
     * @param integer $photoSize
     *
     * @return P_Program
     */
    public function setPhotoSize($photoSize)
    {
        $this->photoSize = $photoSize;

        return $this;
    }

    /**
     * Get photoSize
     *
     * @return integer
     */
    public function getPhotoSize()
    {
        return $this->photoSize;
    }

    /**
     * Set videoLink
     *
     * @param string $videoLink
     *
     * @return P_Program
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    /**
     * Get videoLink
     *
     * @return string
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return P_Program
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set anecdote
     *
     * @param string $anecdote
     *
     * @return P_Program
     */
    public function setAnecdote($anecdote)
    {
        $this->anecdote = $anecdote;

        return $this;
    }

    /**
     * Get anecdote
     *
     * @return string
     */
    public function getAnecdote()
    {
        return $this->anecdote;
    }

    /**
     * Set websiteURL
     *
     * @param string $websiteURL
     *
     * @return P_Program
     */
    public function setWebsiteURL($websiteURL)
    {
        $this->websiteURL = $websiteURL;

        return $this;
    }

    /**
     * Get websiteURL
     *
     * @return string
     */
    public function getWebsiteURL()
    {
        return $this->websiteURL;
    }

    /**
     * Set facebookURL
     *
     * @param string $facebookURL
     *
     * @return P_Program
     */
    public function setFacebookURL($facebookURL)
    {
        $this->facebookURL = $facebookURL;

        return $this;
    }

    /**
     * Get facebookURL
     *
     * @return string
     */
    public function getFacebookURL()
    {
        return $this->facebookURL;
    }

    /**
     * Set twitterURL
     *
     * @param string $twitterURL
     *
     * @return P_Program
     */
    public function setTwitterURL($twitterURL)
    {
        $this->twitterURL = $twitterURL;

        return $this;
    }

    /**
     * Get twitterURL
     *
     * @return string
     */
    public function getTwitterURL()
    {
        return $this->twitterURL;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     *
     * @return P_Program
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     *
     * @return P_Program
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set startOpYear
     *
     * @param integer $startOpYear
     *
     * @return P_Program
     */
    public function setStartOpYear($startOpYear)
    {
        $this->startOpYear = $startOpYear;

        return $this;
    }

    /**
     * Get startOpYear
     *
     * @return integer
     */
    public function getStartOpYear()
    {
        return $this->startOpYear;
    }

    /**
     * Set othersIndicators
     *
     * @param string $othersIndicators
     *
     * @return P_Program
     */
    public function setOthersIndicators($othersIndicators)
    {
        $this->othersIndicators = $othersIndicators;

        return $this;
    }

    /**
     * Get othersIndicators
     *
     * @return string
     */
    public function getOthersIndicators()
    {
        return $this->othersIndicators;
    }

    /**
     * Set dataFeedback
     *
     * @param string $dataFeedback
     *
     * @return P_Program
     */
    public function setDataFeedback($dataFeedback)
    {
        $this->dataFeedback = $dataFeedback;

        return $this;
    }

    /**
     * Get dataFeedback
     *
     * @return string
     */
    public function getDataFeedback()
    {
        return $this->dataFeedback;
    }

    /**
     * Set dataStorageLocalisation
     *
     * @param string $dataStorageLocalisation
     *
     * @return P_Program
     */
    public function setDataStorageLocalisation($dataStorageLocalisation)
    {
        $this->dataStorageLocalisation = $dataStorageLocalisation;

        return $this;
    }

    /**
     * Get dataStorageLocalisation
     *
     * @return string
     */
    public function getDataStorageLocalisation()
    {
        return $this->dataStorageLocalisation;
    }

    /**
     * Set nbETP
     *
     * @param integer $nbETP
     *
     * @return P_Program
     */
    public function setNbETP($nbETP)
    {
        $this->nbETP = $nbETP;

        return $this;
    }

    /**
     * Get nbETP
     *
     * @return integer
     */
    public function getNbETP()
    {
        return $this->nbETP;
    }

    /**
     * Set nbWorkingPersons
     *
     * @param integer $nbWorkingPersons
     *
     * @return P_Program
     */
    public function setNbWorkingPersons($nbWorkingPersons)
    {
        $this->nbWorkingPersons = $nbWorkingPersons;

        return $this;
    }

    /**
     * Get nbWorkingPersons
     *
     * @return integer
     */
    public function getNbWorkingPersons()
    {
        return $this->nbWorkingPersons;
    }

    /**
     * Set financePartners
     *
     * @param string $financePartners
     *
     * @return P_Program
     */
    public function setFinancePartners($financePartners)
    {
        $this->financePartners = $financePartners;

        return $this;
    }

    /**
     * Get financePartners
     *
     * @return string
     */
    public function getFinancePartners()
    {
        return $this->financePartners;
    }

    /**
     * Set status
     *
     * @param \BuilderBundle\Entity\G_ListItem $status
     *
     * @return P_Program
     */
    public function setStatus(\BuilderBundle\Entity\G_ListItem $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getStatus()
    {
        return $this->status;
    }

    // /**
    //  * Add objectives1
    //  *
    //  * @param \BuilderBundle\Entity\G_ListItem $objectives1
    //  *
    //  * @return P_Program
    //  */
    // public function addObjectives1(\BuilderBundle\Entity\G_ListItem $objectives1)
    // {
    //     $this->objectives1[] = $objectives1;

    //     return $this;
    // }

    // /**
    //  * Remove objectives1
    //  *
    //  * @param \BuilderBundle\Entity\G_ListItem $objectives1
    //  */
    // public function removeObjectives1(\BuilderBundle\Entity\G_ListItem $objectives1)
    // {
    //     $this->objectives1->removeElement($objectives1);
    // }

    // /**
    //  * Get objectives1
    //  *
    //  * @return \Doctrine\Common\Collections\Collection
    //  */
    // public function getObjectives1()
    // {
    //     return $this->objectives1;
    // }

    // /**
    //  * Add objectives2
    //  *
    //  * @param \BuilderBundle\Entity\G_ListItem $objectives2
    //  *
    //  * @return P_Program
    //  */
    // public function addObjectives2(\BuilderBundle\Entity\G_ListItem $objectives2)
    // {
    //     $this->objectives2[] = $objectives2;

    //     return $this;
    // }

    // /**
    //  * Remove objectives2
    //  *
    //  * @param \BuilderBundle\Entity\G_ListItem $objectives2
    //  */
    // public function removeObjectives2(\BuilderBundle\Entity\G_ListItem $objectives2)
    // {
    //     $this->objectives2->removeElement($objectives2);
    // }

    // /**
    //  * Get objectives2
    //  *
    //  * @return \Doctrine\Common\Collections\Collection
    //  */
    // public function getObjectives2()
    // {
    //     return $this->objectives2;
    // }

    // /**
    //  * Add objectives3
    //  *
    //  * @param \BuilderBundle\Entity\G_ListItem $objectives3
    //  *
    //  * @return P_Program
    //  */
    // public function addObjectives3(\BuilderBundle\Entity\G_ListItem $objectives3)
    // {
    //     $this->objectives3[] = $objectives3;

    //     return $this;
    // }

    // /**
    //  * Remove objectives3
    //  *
    //  * @param \BuilderBundle\Entity\G_ListItem $objectives3
    //  */
    // public function removeObjectives3(\BuilderBundle\Entity\G_ListItem $objectives3)
    // {
    //     $this->objectives3->removeElement($objectives3);
    // }

    // /**
    //  * Get objectives3
    //  *
    //  * @return \Doctrine\Common\Collections\Collection
    //  */
    // public function getObjectives3()
    // {
    //     return $this->objectives3;
    // }

    /**
     * Add theme
     *
     * @param \BuilderBundle\Entity\G_ListItem $theme
     *
     * @return P_Program
     */
    public function addTheme(\BuilderBundle\Entity\G_ListItem $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme
     *
     * @param \BuilderBundle\Entity\G_ListItem $theme
     */
    public function removeTheme(\BuilderBundle\Entity\G_ListItem $theme)
    {
        $this->themes->removeElement($theme);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Set accessibility
     *
     * @param \BuilderBundle\Entity\G_ListItem $accessibility
     *
     * @return P_Program
     */
    public function setAccessibility(\BuilderBundle\Entity\G_ListItem $accessibility = null)
    {
        $this->accessibility = $accessibility;

        return $this;
    }

    /**
     * Get accessibility
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getAccessibility()
    {
        return $this->accessibility;
    }

    /**
     * Add season
     *
     * @param \BuilderBundle\Entity\G_ListItem $season
     *
     * @return P_Program
     */
    public function addSeason(\BuilderBundle\Entity\G_ListItem $season)
    {
        $this->seasons[] = $season;

        return $this;
    }

    /**
     * Remove season
     *
     * @param \BuilderBundle\Entity\G_ListItem $season
     */
    public function removeSeason(\BuilderBundle\Entity\G_ListItem $season)
    {
        $this->seasons->removeElement($season);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Set structureAnim
     *
     * @param \MNHN\PortailBundle\Entity\P_Structure $structureAnim
     *
     * @return P_Program
     */
    public function setStructureAnim(\MNHN\PortailBundle\Entity\P_Structure $structureAnim = null)
    {
        $this->structureAnim = $structureAnim;

        return $this;
    }

    /**
     * Get structureAnim
     *
     * @return \MNHN\PortailBundle\Entity\P_Structure
     */
    public function getStructureAnim()
    {
        return $this->structureAnim;
    }

    /**
     * Add structuresRespScientist
     *
     * @param \MNHN\PortailBundle\Entity\P_Structure $structuresRespScientist
     *
     * @return P_Program
     */
    public function addStructuresRespScientist(\MNHN\PortailBundle\Entity\P_Structure $structuresRespScientist)
    {
        $this->structuresRespScientist[] = $structuresRespScientist;

        return $this;
    }

    /**
     * Remove structuresRespScientist
     *
     * @param \MNHN\PortailBundle\Entity\P_Structure $structuresRespScientist
     */
    public function removeStructuresRespScientist(\MNHN\PortailBundle\Entity\P_Structure $structuresRespScientist)
    {
        $this->structuresRespScientist->removeElement($structuresRespScientist);
    }

    /**
     * Get structuresRespScientist
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStructuresRespScientist()
    {
        return $this->structuresRespScientist;
    }

    /**
     * Set contactRespScientist
     *
     * @param \Application\Sonata\UserBundle\Entity\User $contactRespScientist
     *
     * @return P_Program
     */
    public function setContactRespScientist(\Application\Sonata\UserBundle\Entity\User $contactRespScientist = null)
    {
        $this->contactRespScientist = $contactRespScientist;

        return $this;
    }

    /**
     * Get contactRespScientist
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getContactRespScientist()
    {
        return $this->contactRespScientist;
    }

    /**
     * Set networkObs
     *
     * @param \BuilderBundle\Entity\G_ListItem $networkObs
     *
     * @return P_Program
     */
    public function setNetworkObs(\BuilderBundle\Entity\G_ListItem $networkObs = null)
    {
        $this->networkObs = $networkObs;

        return $this;
    }

    /**
     * Get networkObs
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getNetworkObs()
    {
        return $this->networkObs;
    }

    /**
     * Add relayLocal
     *
     * @param \MNHN\PortailBundle\Entity\P_Structure $relayLocal
     *
     * @return P_Program
     */
    public function addRelayLocal(\MNHN\PortailBundle\Entity\P_Structure $relayLocal)
    {
        $this->relayLocal[] = $relayLocal;

        return $this;
    }

    /**
     * Remove relayLocal
     *
     * @param \MNHN\PortailBundle\Entity\P_Structure $relayLocal
     */
    public function removeRelayLocal(\MNHN\PortailBundle\Entity\P_Structure $relayLocal)
    {
        $this->relayLocal->removeElement($relayLocal);
    }

    /**
     * Get relayLocal
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelayLocal()
    {
        return $this->relayLocal;
    }

    /**
     * Set programDuration
     *
     * @param \BuilderBundle\Entity\G_ListItem $programDuration
     *
     * @return P_Program
     */
    public function setProgramDuration(\BuilderBundle\Entity\G_ListItem $programDuration = null)
    {
        $this->programDuration = $programDuration;

        return $this;
    }

    /**
     * Get programDuration
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getProgramDuration()
    {
        return $this->programDuration;
    }

    /**
     * Set suscriberDurationMoy
     *
     * @param \BuilderBundle\Entity\G_ListItem $suscriberDurationMoy
     *
     * @return P_Program
     */
    public function setSuscriberDurationMoy(\BuilderBundle\Entity\G_ListItem $suscriberDurationMoy = null)
    {
        $this->suscriberDurationMoy = $suscriberDurationMoy;

        return $this;
    }

    /**
     * Get suscriberDurationMoy
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getSuscriberDurationMoy()
    {
        return $this->suscriberDurationMoy;
    }

    /**
     * Set programQualityEvolution
     *
     * @param \BuilderBundle\Entity\G_ListItem $programQualityEvolution
     *
     * @return P_Program
     */
    public function setProgramQualityEvolution(\BuilderBundle\Entity\G_ListItem $programQualityEvolution = null)
    {
        $this->programQualityEvolution = $programQualityEvolution;

        return $this;
    }

    /**
     * Get programQualityEvolution
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getProgramQualityEvolution()
    {
        return $this->programQualityEvolution;
    }

    /**
     * Set programDataValidator
     *
     * @param \BuilderBundle\Entity\G_ListItem $programDataValidator
     *
     * @return P_Program
     */
    public function setProgramDataValidator(\BuilderBundle\Entity\G_ListItem $programDataValidator = null)
    {
        $this->programDataValidator = $programDataValidator;

        return $this;
    }

    /**
     * Get programDataValidator
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getProgramDataValidator()
    {
        return $this->programDataValidator;
    }

    /**
     * Set programDataAnalyser
     *
     * @param \BuilderBundle\Entity\G_ListItem $programDataAnalyser
     *
     * @return P_Program
     */
    public function setProgramDataAnalyser(\BuilderBundle\Entity\G_ListItem $programDataAnalyser = null)
    {
        $this->programDataAnalyser = $programDataAnalyser;

        return $this;
    }

    /**
     * Get programDataAnalyser
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getProgramDataAnalyser()
    {
        return $this->programDataAnalyser;
    }

    /**
     * Set programDataFormat
     *
     * @param \BuilderBundle\Entity\G_ListItem $programDataFormat
     *
     * @return P_Program
     */
    public function setProgramDataFormat(\BuilderBundle\Entity\G_ListItem $programDataFormat = null)
    {
        $this->programDataFormat = $programDataFormat;

        return $this;
    }

    /**
     * Get programDataFormat
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getProgramDataFormat()
    {
        return $this->programDataFormat;
    }

    /**
     * Set protocolSINP
     *
     * @param \BuilderBundle\Entity\G_ListItem $protocolSINP
     *
     * @return P_Program
     */
    public function setProtocolSINP(\BuilderBundle\Entity\G_ListItem $protocolSINP = null)
    {
        $this->protocolSINP = $protocolSINP;

        return $this;
    }

    /**
     * Get protocolSINP
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getProtocolSINP()
    {
        return $this->protocolSINP;
    }

    /**
     * Set annualBudget
     *
     * @param \BuilderBundle\Entity\G_ListItem $annualBudget
     *
     * @return P_Program
     */
    public function setAnnualBudget(\BuilderBundle\Entity\G_ListItem $annualBudget = null)
    {
        $this->annualBudget = $annualBudget;

        return $this;
    }

    /**
     * Get annualBudget
     *
     * @return \BuilderBundle\Entity\G_ListItem
     */
    public function getAnnualBudget()
    {
        return $this->annualBudget;
    }

    /**
     * Set stuff
     *
     * @param string $stuff
     *
     * @return P_Program
     */
    public function setStuff($stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return string
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * Set results
     *
     * @param string $results
     *
     * @return P_Program
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return string
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Add ownersAnim
     *
     * @param \Application\Sonata\UserBundle\Entity\User $ownersAnim
     *
     * @return P_Program
     */
    public function addOwnersAnim(\Application\Sonata\UserBundle\Entity\User $ownersAnim)
    {
        $this->ownersAnim[] = $ownersAnim;

        return $this;
    }

    /**
     * Remove ownersAnim
     *
     * @param \Application\Sonata\UserBundle\Entity\User $ownersAnim
     */
    public function removeOwnersAnim(\Application\Sonata\UserBundle\Entity\User $ownersAnim)
    {
        $this->ownersAnim->removeElement($ownersAnim);
    }

    /**
     * Get ownersAnim
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnersAnim()
    {
        return $this->ownersAnim;
    }

    /**
     * Set structureRespScientist
     *
     * @param \MNHN\PortailBundle\Entity\P_Structure $structureRespScientist
     *
     * @return P_Program
     */
    public function setStructureRespScientist(\MNHN\PortailBundle\Entity\P_Structure $structureRespScientist = null)
    {
        $this->structureRespScientist = $structureRespScientist;

        return $this;
    }

    /**
     * Get structureRespScientist
     *
     * @return \MNHN\PortailBundle\Entity\P_Structure
     */
    public function getStructureRespScientist()
    {
        return $this->structureRespScientist;
    }

    /**
     * Add objective
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_Objective $objective
     *
     * @return P_Program
     */
    public function addObjective(\MNHN\PortailBundle\Entity\P_Program_Objective $objective)
    {
        $this->objectives[] = $objective;

        return $this;
    }

    /**
     * Remove objective
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_Objective $objective
     */
    public function removeObjective(\MNHN\PortailBundle\Entity\P_Program_Objective $objective)
    {
        $this->objectives->removeElement($objective);
    }

    /**
     * Get objectives
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * Add nbSubscriberByYear
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbSubscriberByYear
     *
     * @return P_Program
     */
    public function addNbSubscriberByYear(\MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbSubscriberByYear)
    {
        $this->nbSubscriberByYear[] = $nbSubscriberByYear;

        return $this;
    }

    /**
     * Remove nbSubscriberByYear
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbSubscriberByYear
     */
    public function removeNbSubscriberByYear(\MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbSubscriberByYear)
    {
        $this->nbSubscriberByYear->removeElement($nbSubscriberByYear);
    }

    /**
     * Get nbSubscriberByYear
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNbSubscriberByYear()
    {
        return $this->nbSubscriberByYear;
    }

    /**
     * Add nbActiveUserByYear
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbActiveUserByYear
     *
     * @return P_Program
     */
    public function addNbActiveUserByYear(\MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbActiveUserByYear)
    {
        $this->nbActiveUserByYear[] = $nbActiveUserByYear;

        return $this;
    }

    /**
     * Remove nbActiveUserByYear
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbActiveUserByYear
     */
    public function removeNbActiveUserByYear(\MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbActiveUserByYear)
    {
        $this->nbActiveUserByYear->removeElement($nbActiveUserByYear);
    }

    /**
     * Get nbActiveUserByYear
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNbActiveUserByYear()
    {
        return $this->nbActiveUserByYear;
    }

    /**
     * Add nbCollectedDataByYear
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbCollectedDataByYear
     *
     * @return P_Program
     */
    public function addNbCollectedDataByYear(\MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbCollectedDataByYear)
    {
        $this->nbCollectedDataByYear[] = $nbCollectedDataByYear;

        return $this;
    }

    /**
     * Remove nbCollectedDataByYear
     *
     * @param \MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbCollectedDataByYear
     */
    public function removeNbCollectedDataByYear(\MNHN\PortailBundle\Entity\P_Program_ValueByYear $nbCollectedDataByYear)
    {
        $this->nbCollectedDataByYear->removeElement($nbCollectedDataByYear);
    }

    /**
     * Get nbCollectedDataByYear
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNbCollectedDataByYear()
    {
        return $this->nbCollectedDataByYear;
    }

    /**
     * Add taxon
     *
     * @param \BuilderBundle\Entity\G_ListItem $taxon
     *
     * @return P_Program
     */
    public function addTaxon(\BuilderBundle\Entity\G_ListItem $taxon)
    {
        $this->taxons[] = $taxon;
        return $this;
    }

    /**
     * Remove taxon
     *
     * @param \BuilderBundle\Entity\G_ListItem $taxon
     */
    public function removeTaxon(\BuilderBundle\Entity\G_ListItem $taxon)
    {
        $this->taxons->removeElement($taxon);
    }

    /**
     * Get taxons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaxons()
    {
        return $this->taxons;
    }

    public function setLocalisations($localisations)
    {
        if (count($localisations) > 0) {
            $old_localisations = $this->getLocalisations();
            foreach ($old_localisations as $e) {
                $this->removeLocalisation($e);
            }
            foreach ($localisations as $localisation) {
                $this->addLocalisation($localisation);
            }
        }

        return $this;
    }

    /**
     * @param Geom \MNHN\PortailBundle\Entity\Geom $localisations
     */
    public function addLocalisation(\MNHN\PortailBundle\Entity\Geom  $localisations)
    {
        $this->localisations[] = $localisations;
    }

    /**
     * @param Geom \MNHN\PortailBundle\Entity\Geom $localisations
     */
    public function removeLocalisation(\MNHN\PortailBundle\Entity\Geom $localisation)
    {
        $this->localisations->removeElement($localisation);
    }

    /**
     * @return mixed
     */
    public function getLocalisations()
    {
        return $this->localisations;
    }

    public function setPartners($partners)
    {
        if (count($partners) > 0) {
            foreach ($partners as $i) {
                $this->addPartners($i);
            }
        }

        return $this;
    }
    
    /**
     * Add partners
     *
     * @param \MNHN\PortailBundle\Entity\Partners $partners
     *
     */
    public function addPartners(\MNHN\PortailBundle\Entity\Partners $partners)
    {
        $partners->setProgram($this);
        $this->partners->add($partners);
    }
    
    /**
     * Remove partners
     *
     * @param \MNHN\PortailBundle\Entity\Partners $partners
     */
    public function removePartners(\MNHN\PortailBundle\Entity\Partners $partners)
    {
        $this->partners->removeElement($partners);
        $partners->setProgram(null);
    }
    
    /**
     * Get partners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPartners()
    {
        return $this->partners;
    }

    public function setResultsPdf($resultsPdf)
    {
        if (count($resultsPdf) > 0) {
            foreach ($resultsPdf as $i) {

                $this->addResultsPdf($i);
            }
        }
        return $this;
    }

    /**
     * Add resultsPdf
     *
     * @param \MNHN\PortailBundle\Entity\Program_Results $resultsPdf
     *
     */
    public function addResultsPdf(\MNHN\PortailBundle\Entity\Program_Results $resultsPdf)
    {
        $resultsPdf->setProgram($this);
        $this->resultsPdf->add($resultsPdf);
    }

    /**
     * Remove resultsPdf
     *
     * @param \MNHN\PortailBundle\Entity\Partners $resultsPdf
     */
    public function removeResultsPdf(\MNHN\PortailBundle\Entity\Program_Results $resultsPdf)
    {
        $this->resultsPdf->removeElement($resultsPdf);
    }

    /**
     * Get resultsPdf
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultsPdf()
    {
        return $this->resultsPdf;
    }


    /**
     * Set isFinalizedForm
     *
     * @param boolean $isFinalizedForm
     *
     * @return P_Structure
     */
    public function setIsFinalizedForm($isFinalizedForm)
    {
        $this->isFinalizedForm = $isFinalizedForm;

        return $this;
    }

    /**
     * Get isFinalizedForm
     *
     * @return bool
     */
    public function getIsFinalizedForm()
    {
        return $this->isFinalizedForm;
    }

    /**
     * Set isActiveProgram
     *
     * @param boolean $isActiveProgram
     *
     * @return P_Structure
     */
    public function setIsActiveProgram($isActiveProgram)
    {
        $this->isActiveProgram = $isActiveProgram;

        return $this;
    }

    /**
     * Get isActiveProgram
     *
     * @return bool
     */
    public function getIsActiveProgram()
    {
        return $this->isActiveProgram;
    }
}