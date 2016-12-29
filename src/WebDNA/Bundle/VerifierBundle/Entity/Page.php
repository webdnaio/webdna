<?php

namespace WebDNA\Bundle\VerifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="WebDNA\Bundle\VerifierBundle\Repository\PageRepository")
 */
class Page extends Base\Page
{
    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        $this->setDomain(parse_url($url, PHP_URL_HOST));

        return parent::setUrl($url);
    }
}
