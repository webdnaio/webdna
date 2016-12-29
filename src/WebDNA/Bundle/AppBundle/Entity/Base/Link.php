<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Link
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class Link
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
     * @var \WebDNA\Bundle\AppBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Page")
     * @ORM\JoinColumn(name="source_page_id", referencedColumnName="id", nullable=false)
     */
    protected $sourcePage;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Page")
     * @ORM\JoinColumn(name="target_page_id", referencedColumnName="id")
     */
    protected $targetPage;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=512)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="anchor", type="string", length=128)
     */
    protected $anchor;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;


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
     * Set type
     *
     * @param integer $type
     * @return Link
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Link
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
     * Set url
     *
     * @param string $url
     * @return Link
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set anchor
     *
     * @param string $anchor
     * @return Link
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;

        return $this;
    }

    /**
     * Get anchor
     *
     * @return string
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Link
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
     * Set sourcePage
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Page $sourcePage
     * @return Link
     */
    public function setSourcePage(\WebDNA\Bundle\AppBundle\Entity\Page $sourcePage)
    {
        $this->sourcePage = $sourcePage;

        return $this;
    }

    /**
     * Get sourcePage
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\Page
     */
    public function getSourcePage()
    {
        return $this->sourcePage;
    }

    /**
     * Set targetPage
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Page $targetPage
     * @return Link
     */
    public function setTargetPage(\WebDNA\Bundle\AppBundle\Entity\Page $targetPage = null)
    {
        $this->targetPage = $targetPage;

        return $this;
    }

    /**
     * Get targetPage
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\Page
     */
    public function getTargetPage()
    {
        return $this->targetPage;
    }
}
