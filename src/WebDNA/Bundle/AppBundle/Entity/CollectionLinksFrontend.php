<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 14.03.2014
 * Time: 11:39
 */

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use WebDNA\Bundle\AppBundle\Entity\LinkFrontend;

class CollectionLinksFrontend
{
    protected $links;

    protected $error_links;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->errorLinks = new ArrayCollection();
    }


    public function addLinks($validator, array $uploadLinks)
    {
        if (count($uploadLinks)) {
            foreach ($uploadLinks as $uploadLink) {
                $link = new LinkFrontend();
                $link->name = $this->clearLinks($uploadLink);

                $errors = $validator->validate($link);
                (count($errors) > 0) ? $this->getErrorLinks()->add($link) : $this->getLinks()->add($link);
            }
        }
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getErrorLinks()
    {
        return $this->errorLinks;
    }

    protected function clearLinks($link)
    {
        $wrongChars = array('"');

        return str_replace($wrongChars, '', $link);
    }
}
