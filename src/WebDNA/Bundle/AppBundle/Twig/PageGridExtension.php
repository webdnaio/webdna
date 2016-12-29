<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use WebDNA\Bundle\AppBundle\Entity\Page;

class PageGridExtension extends \Twig_Extension
{
    protected $itemMetricExtension;

    public function __construct($itemMetricExtension)
    {
        $this->itemMetricExtension = $itemMetricExtension;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_pages', array($this, 'getPages')),
        );
    }

    /**
     * @param array $pageItemAnalyzes
     * @param array $itemMetrics
     * @param array $metrics
     * @return array
     */
    public function getPages(array $pageItemAnalyzes, array $itemMetrics, array $metrics)
    {
        $pages = [];

        foreach ($pageItemAnalyzes as $page) {
            $pages[] = [
                    'metrics' => $this->itemMetricExtension
                        ->getMetrics($itemMetrics[$page['itemAnalysisId']], $metrics),
                    'page'    => $page
                ];
        }

        return $pages;
    }

    public function getName()
    {
        return 'page_grid';
    }
}
