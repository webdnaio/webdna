<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use WebDNA\Bundle\AppBundle\Validator\Constraints as ValidationAssert;

/**
 * Class Website
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class Website
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\WebDNA\Bundle\CommonBundle\Doctrine\ORM\Id\BigIntGenerator")
     */
    protected $id;

    /**
     * @var \WebDNA\Bundle\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis")
     * @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id")
     */
    protected $itemAnalysis;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status;

    /**
     * @Assert\NotBlank
     * @ValidationAssert\ContainsDomain
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=128, nullable=true)
     */
    protected $image_path;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Page")
     * @ORM\JoinTable(name="website_page",
     *   joinColumns={
     *     @ORM\JoinColumn(name="website_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $pages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Link")
     * @ORM\JoinTable(name="website_link",
     *   joinColumns={
     *     @ORM\JoinColumn(name="website_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="link_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $links;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess", cascade={"persist"})
     * @ORM\JoinTable(name="website_analysis_process",
     *   joinColumns={
     *     @ORM\JoinColumn(name="website_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $analysisProcesses;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis")
     * @ORM\JoinTable(name="website_item_analysis",
     *   joinColumns={
     *     @ORM\JoinColumn(name="website_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $itemAnalyzes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
        $this->analysisProcesses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemAnalyzes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set status
     *
     * @param integer $status
     * @return Website
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Website
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
     * Set image path
     *
     * @param string $imagePath
     * @return Website
     */
    public function setImagePath($imagePath)
    {
        $this->image_path = $imagePath;

        return $this;
    }

    /**
     * Get image path
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->image_path;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Website
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Website
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set user
     *
     * @param \WebDNA\Bundle\UserBundle\Entity\User $user
     * @return Website
     */
    public function setUser(\WebDNA\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \WebDNA\Bundle\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set itemAnalysis
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     * @return Website
     */
    public function setItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis = null)
    {
        $this->itemAnalysis = $itemAnalysis;

        return $this;
    }

    /**
     * Get itemAnalysis
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     */
    public function getItemAnalysis()
    {
        return $this->itemAnalysis;
    }

    /**
     * Add page
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Page $page
     * @return Website
     */
    public function addPage(\WebDNA\Bundle\AppBundle\Entity\Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Page $page
     */
    public function removePage(\WebDNA\Bundle\AppBundle\Entity\Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add link
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Link $link
     * @return Website
     */
    public function addLink(\WebDNA\Bundle\AppBundle\Entity\Link $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * Remove link
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Link $link
     */
    public function removeLink(\WebDNA\Bundle\AppBundle\Entity\Link $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Add analysisProcess
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess
     * @return Website
     */
    public function addAnalysisProcess(\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess)
    {
        $analysisProcess->setWebsite($this);

        $this->analysisProcesses[] = $analysisProcess;

        return $this;
    }

    /**
     * Remove analysisProcess
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess
     */
    public function removeAnalysisProcess(\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess)
    {
        $this->analysisProcesses->removeElement($analysisProcess);
    }

    /**
     * Get analysisProcesses
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess[] $analysisProcess
     */
    public function getAnalysisProcesses()
    {
        return $this->analysisProcesses;
    }

    /**
     * Add itemAnalysis
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     * @return Website
     */
    public function addItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis)
    {
        $this->itemAnalyzes[] = $itemAnalysis;

        return $this;
    }

    /**
     * Remove itemAnalysis
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     */
    public function removeItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis)
    {
        $this->itemAnalyzes->removeElement($itemAnalysis);
    }
}
