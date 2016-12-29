<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Repository\Service\ItemAnalysisService;

class ItemAnalysisExtension extends \Twig_Extension
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Service\ItemAnalysisService
     */
    protected $itemAnalysisService;

    /**
     * @param ItemAnalysisService $itemAnalysisService
     */
    public function __construct(ItemAnalysisService $itemAnalysisService)
    {
        $this->itemAnalysisService = $itemAnalysisService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('analyzed_object', array($this, 'analyzedObject')),
            new \Twig_SimpleFunction('get_analysis_status_label', array($this, 'getStatusLabel')),
            new \Twig_SimpleFunction('count_classes', array($this, 'getCountClasses')),
        );
    }

    /**
     * @param int $status
     */
    public function getStatusLabel($status)
    {
        return AnalysisProcess::$statusLabels[$status];
    }

    /**
     * @param int $itemAnalysisId
     * @return array
     */
    public function getCountClasses($itemAnalysisId)
    {
        return $this->itemAnalysisService->countItemClasses($itemAnalysisId);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item_analysis_extension';
    }

    /**
     * @param $itemAnalysis
     * @return mixed
     */
    public function analyzedObject($itemAnalysis)
    {
        return $this->itemAnalysisService->getAnalyzedObject($itemAnalysis);
    }
}
