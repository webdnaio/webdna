<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use Coduo\PHPHumanizer\DateTime;

class HumanizerExtension extends \Twig_Extension
{
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('humanize_time', array($this, 'getTimeDifference')),
        );
    }

    /**
     * @param string $date|\DateTime object
     * @return \DateTime
     */
    public function getTimeDifference($date)
    {
        if (!is_a($date, 'DateTime')) {
            $date = new \DateTime($date);
        }
        return DateTime::difference(new \DateTime('now'), $date);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'humanizer';
    }
}
