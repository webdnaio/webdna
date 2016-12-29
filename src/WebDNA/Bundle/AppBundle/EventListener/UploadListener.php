<?php

namespace WebDNA\Bundle\AppBundle\EventListener;

use Gaufrette\Filesystem;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\ValidationEvent;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessInputService;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessService;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessUrlsService;

class UploadListener
{

    /**
     *
     */
    const UPLOAD_STATUS_SUCCESS = 1;
    const UPLOAD_STATUS_FAILED = 2;

    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessService
     */
    protected $analysisProcessService;

    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessInputService
     */
    protected $analysisProcessInputService;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $status;

    /**
     * @param AnalysisProcessService $analysisProcessService
     * @param AnalysisProcessInputService $analysisProcessInputService
     * @param Filesystem $fileService
     * @param AnalysisProcessUrlsService $analysisProcessUrlsService
     */
    public function __construct(
        AnalysisProcessService $analysisProcessService,
        AnalysisProcessInputService $analysisProcessInputService,
        Filesystem $fileService,
        AnalysisProcessUrlsService $analysisProcessUrlsService
    ) {
        ini_set('memory_limit', '2G');
        set_time_limit(0);
        $this->analysisProcessService = $analysisProcessService;
        $this->analysisProcessInputService = $analysisProcessInputService;
        $this->fileService = $fileService;
        $this->analysisProcessUrlsService = $analysisProcessUrlsService;
    }

    /**
     * @param PostPersistEvent $event
     */
    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();

        $analysisProcess = $this->analysisProcessService->find($request->get('analysis_process_id'));

        if ($analysisProcess instanceof AnalysisProcess) {
            $analysisProcessInput = $this->analysisProcessInputService->create();

            $analysisProcessInput->setType(AnalysisProcessInput::TYPE_FILE);
            $analysisProcessInput->setStatus(AnalysisProcessInput::STATUS_NEW);
            $analysisProcessInput->setPath($event->getFile()->getPathname());

            $analysisProcess->addAnalysisProcessInput($analysisProcessInput);

            $this->analysisProcessInputService->save($analysisProcessInput);
            $this->analysisProcessService->save($analysisProcess);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->message) {
            $event->setResponse(new JsonResponse('{' . $this->message . '}', 415));
        }
    }

    public function onValidate(ValidationEvent $event)
    {
        $allowedTypes = [
            'text/plain'
        ];

        $data = file_get_contents($event->getFile()->getPathname());
        if (
            in_array($event->getFile()->getMimeType(), $allowedTypes)
            && $this->analysisProcessUrlsService->getUrlsIterator($data)->count() > 0
        ) {
            $this->status = self::UPLOAD_STATUS_SUCCESS;
        } else {
            $this->status = self::UPLOAD_STATUS_FAILED;
            $this->message = 'File of type ' . $event->getFile()->getMimeType() . ' is not allowed';
        }
    }
}
