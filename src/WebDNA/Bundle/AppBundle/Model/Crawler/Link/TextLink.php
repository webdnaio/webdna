<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler\Link;

/**
 * Class TextLink
 * @package WebDNA\Bundle\AppBundle\Model\Crawler\Link
 */
class TextLink extends Link
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return \WebDNA\Bundle\AppBundle\Entity\Link::TYPE_TEXT;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeLabel()
    {
        return 'text';
    }
}
