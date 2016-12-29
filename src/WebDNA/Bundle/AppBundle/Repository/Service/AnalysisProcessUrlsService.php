<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use Symfony\Component\Validator\ValidatorInterface;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Iterator\UrlsFilterIterator;
use WebDNA\Bundle\AppBundle\Uploader\Naming\AnalysisInputUploadNamer;

/**
 * Class AnalysisProcessInputService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class AnalysisProcessUrlsService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessInputService
     */
    protected $analysisProcessInputService;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var AnalysisInputUploadNamer
     */
    protected $namer;

    /**
     * @param AnalysisProcessService $analysisProcessService
     * @param AnalysisProcessInputService $analysisProcessInputService
     * @param ValidatorInterface $validator
     * @param AnalysisInputUploadNamer $namer
     */
    public function __construct(
        AnalysisProcessService $analysisProcessService,
        AnalysisProcessInputService $analysisProcessInputService,
        ValidatorInterface $validator,
        AnalysisInputUploadNamer $namer
    ) {
        $this->analysisProcessService = $analysisProcessService;
        $this->analysisProcessInputService = $analysisProcessInputService;
        $this->validator = $validator;
        $this->namer = $namer;
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $urls
     * @return AnalysisProcessInput $analysisProcessInput
     */
    public function saveUrls(AnalysisProcess $analysisProcess, array $urls)
    {
        if (empty($urls)) {
            new \Exception('There are no urls in provided input');
        }

        $website = $analysisProcess->getWebsite();

        $analysisProcessInput = $this->analysisProcessInputService->create();

        $analysisProcessInput->setType(AnalysisProcessInput::TYPE_FILE);
        $analysisProcessInput->setStatus(AnalysisProcessInput::STATUS_NEW);
        $path = $this->namer->generatePath($website->getName() . '_' . time() . '.txt');
        $analysisProcessInput->setPath($path);
        $analysisProcessInput->setAnalysisProcess($analysisProcess);

        $this->analysisProcessInputService->saveData($analysisProcessInput, implode("\n", $urls));
        $this->analysisProcessInputService->save($analysisProcessInput);

        return $analysisProcessInput;
    }

    /**
     * @param AnalysisProcessInput $analysisProcessInput
     * @return UrlsFilterIterator
     */
    public function getUrls(AnalysisProcessInput $analysisProcessInput)
    {
        $data = $this->analysisProcessInputService
            ->readTextData($this->analysisProcessInputService->readData($analysisProcessInput));
        return $this->getUrlsIterator($data);
    }

    /**
     * @param string $data
     * @return UrlsFilterIterator
     */
    public function getUrlsIterator($data)
    {
        // We can rely on new-line character in this place, because service class
        // do cross-platform conversion
        ini_set('memory_limit', '2G');
        $urlsArray = explode(PHP_EOL, $data);

        // Mix urls for better domain distribution
        shuffle($urlsArray);

        return new UrlsFilterIterator(
            new \ArrayIterator($urlsArray),
            $this->validator
        );
    }
}
