<?php

namespace WebDNA\Bundle\AppBundle\Event\Analysis;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Page;

/**
 * Class PageEvent
 * @package WebDNA\Bundle\AppBundle\Event\Analysis
 */
abstract class PageEvent extends AnalysisEvent
{
    /**
     * @var Page
     */
    protected $page;

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Page $page
     */
    public function __construct(AnalysisProcess $analysisProcess, Page $page)
    {
        parent::__construct($analysisProcess);

        $this->page = $page;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
