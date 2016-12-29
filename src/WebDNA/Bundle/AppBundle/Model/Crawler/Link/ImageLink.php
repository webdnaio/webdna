<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler\Link;

/**
 * Class ImageLink
 * @package WebDNA\Bundle\AppBundle\Model\Crawler\Link
 */
class ImageLink extends Link
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return \WebDNA\Bundle\AppBundle\Entity\Link::TYPE_IMAGE;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeLabel()
    {
        return 'image';
    }
}
