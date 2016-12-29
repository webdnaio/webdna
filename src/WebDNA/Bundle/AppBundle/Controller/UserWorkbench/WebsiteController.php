<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverDomainType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverLinksAfterUploadType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverLinksInlineType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverLinksMOZType;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessService;

/**
 * Class WebsiteController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/dashboard/website")
 */
class WebsiteController extends BaseController
{

    /**
     * @Route("/add", name="user_workbench_website_add")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function addAction(Request $request)
    {
        $websiteService = $this->get('websites');
        $analysisProcessService = $this->get('analysis_processes');

        $website = $websiteService->create();

        $website->setUser($this->getUser());
        $website->setName(trim($request->get('discover_domain')['name']));

        $form = $this->createForm(new DiscoverDomainType(), $website);

        $form->handleRequest($request);

        if (!$form->isValid()) {
            $website = $websiteService->findUserWebsiteByName($form->get('name')->getData(), $this->getUser());
            if ($website instanceof Website && $website->getName() !== '') {
                $statusRedirected = $this->redirectIfStatusPreparing($analysisProcessService, $website);
                if ($statusRedirected !== false) {
                    return $statusRedirected;
                }
            }
        } elseif ($request->getMethod() == 'POST') {
            $analysisProcess = $analysisProcessService->create();
            $analysisProcess->setType(AnalysisProcess::TYPE_DISCOVER);
            $analysisProcess->setStatus(AnalysisProcess::STATUS_PREPARING);

            $website->setName($websiteService->parseName($form->get('name')->getData()));
            $website->addAnalysisProcess($analysisProcess);

            $websiteService->save($website);
            $analysisProcessService->save($analysisProcess);

            return $this->redirectToDataSubmission($analysisProcess);
        }

        return array(
            'submission_step' => 1,
            'form' => $form->createView(),
        );
    }

    /**
     * @param AnalysisProcessService $analysisProcessService
     * @param Website $website
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectIfStatusPreparing(AnalysisProcessService $analysisProcessService, Website $website)
    {
        $analysisProcess = $analysisProcessService->findOneByWebsite($website->getId());
        switch ($analysisProcess->getStatus()) {
            case AnalysisProcess::STATUS_FAILED:
            case AnalysisProcess::STATUS_PREPARING:
                return $this->redirectToDataSubmission($analysisProcess);
                break;
            case AnalysisProcess::STATUS_COMPLETED:
                return $this->redirect(
                    $this->generateUrl(
                        'user_workbench_website_repeat_analysis',
                        [
                            'analysisProcess' => $analysisProcess->getId(),
                            'website' => $website->getId(),
                        ]
                    )
                );
                break;
        }

        return false;
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToDataSubmission(AnalysisProcess $analysisProcess)
    {
        return $this->redirect(
            $this->generateUrl(
                'user_workbench_website_data',
                ['analysisProcess' => $analysisProcess->getId()]
            )
        );
    }

    /**
     * @Route("/success/{website}/{analysisProcess}", name="user_workbench_website_submit_success")
     * @Template()
     * @param Website $website
     * @param AnalysisProcess $analysisProcess
     * @return array
     */
    public function addSuccessAction(Website $website, AnalysisProcess $analysisProcess = null)
    {
        return [
            'submission_step' => 3,
            'process' => $analysisProcess,
        ];
    }

    /**
     * @Route("/repeat/{website}", name="user_workbench_website_repeat_analysis")
     * @param Website $website
     * @return array
     */
    public function repeatAction(Website $website)
    {
        $analysisProcessService = $this->get('analysis_processes');

        if ($analysisProcessService->isAnyPendingByWebsite($website->getId()) === true) {
            return $this->redirect(
                $this->generateUrl('user_workbench_website_submit_success', ['website' => $website])
            );
        }

        $websiteService = $this->get('websites');

        $website = $websiteService->find($website->getId());

        if (!$website) {
            throw new NotFoundResourceException();
        }

        /**
         * Clear previous website repeat analyzes settings
         */
        $analysisProcessService->clearRepeat($website);

        /**
         * Set repeat action on the current analysis
         */
        $analysisProcess = $analysisProcessService->create();
        $analysisProcess->setType(AnalysisProcess::TYPE_DISCOVER);
        $analysisProcess->setRepeat(1);
        $date = new \DateTime();
        $analysisProcess->setRepeatAt($date->add(new \DateInterval('P7D')));
        $analysisProcess->setStatus(AnalysisProcess::STATUS_PREPARING);
        $analysisProcess->setWebsite($website);

        $analysisProcessService->save($analysisProcess);

        return $this->redirect(
            $this->generateUrl(
                'user_workbench_website_data',
                array('analysisProcess' => $analysisProcess->getId())
            )
        );
    }

    /**
     *  Step of include data, api or links from file.
     *
     * @Route("/data/{analysisProcess}", name="user_workbench_website_data", options={"expose"=true})
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @return array
     * @Template()
     */
    public function dataAction(Request $request, AnalysisProcess $analysisProcess)
    {
        $website = $analysisProcess->getWebsite();
        $forms = $this->getForms();

        if ($request->getMethod() == 'POST') {
            // Detect submitted form
            foreach ($forms as $type => $form) {
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $values = $form->getData();
                    $urls = array();

                    $analysisProcessService = $this->get('analysis_processes');

                    switch ($type) {
                        case 'inline':
                            $urls = array_merge($urls, preg_split('/\R/', $values['links']));
                            $analysisProcessInput = $this->get('analysis_process_urls')
                                ->saveUrls($analysisProcess, $urls);
                            $analysisProcess->addAnalysisProcessInput($analysisProcessInput);
                            $analysisProcess->setStatus(AnalysisProcess::STATUS_READY_TO_PROCESS);
                            break;
                        case 'moz':
                            $analysisProcess->setStatus(AnalysisProcess::STATUS_FETCHING_BACKLINKS);
                            $analysisProcessService->save($analysisProcess);

                            // delegate background task
                            $this->get('old_sound_rabbit_mq.backlinks_producer')->publish(
                                serialize(['analysisProcessId' => $analysisProcess->getId()]),
                                '',
                                array(),
                                $this->container->getParameter('queue_backlinks_parallelism')
                            );
                            break;
                        case 'afterUpload':
                            $analysisProcess->setStatus(AnalysisProcess::STATUS_READY_TO_PROCESS);
                            break;
                    }

                    $analysisProcess->setRepeat(1);
                    $date = new \DateTime();
                    $analysisProcess->setRepeatAt($date->add(new \DateInterval('P7D')));

                    $analysisProcessService->addPreviousAnalysisProcessInputs($analysisProcess);

                    $analysisProcessService->save($analysisProcess);

                    return $this->redirect(
                        $this->generateUrl(
                            'user_workbench_website_submit_success',
                            ['website' => $website->getId(), 'analysisProcess' => $analysisProcess->getId()]
                        )
                    );
                }
            }
        }

        return array(
            'submission_step' => 2,
            'analysisProcess' => $analysisProcess,
            'website' => $website,
            'inlineForm' => $forms['inline']->createView(),
            'mozForm' => $forms['moz']->createView(),
            'afterUploadForm' => $forms['afterUpload']->createView(),
        );
    }

    /**
     * @return Form[]
     */
    protected function getForms()
    {
        return array(
            'inline' => $this->createForm(new DiscoverLinksInlineType()),
            'moz' => $this->createForm(new DiscoverLinksMOZType()),
            'afterUpload' => $this->createForm(new DiscoverLinksAfterUploadType()),
        );
    }
}
