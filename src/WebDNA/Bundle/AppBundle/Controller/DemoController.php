<?php

namespace WebDNA\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverDomainType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverLinksFileUploadType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverLinksInlineType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverLinksMOZType;
use WebDNA\Bundle\AppBundle\Form\Type\DiscoverNotifyType;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeDomainQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeUrlQueueCommand;
use WebDNA\Bundle\UserBundle\Entity\ContactData;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Class DiscoverController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/demo")
 */
class DemoController extends Controller
{
    /**
     * Initial step of discover demo process.
     *
     * @Route("", name="demo_index")
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $websiteService = $this->get('websites');
        $analysisProcessService = $this->get('analysis_processes');

        $domain = $request->get('discover_domain')['name'];
        $user = $this->get('users')->findByUsername('anonymous@webdna.io');

        $website = $websiteService->findUserWebsiteByName($domain, $user);

        if (is_null($website)) {
            $website = $websiteService->create();
            $website->setUser($user);
            $website->setName($domain);
        } else {
            $analysisProcess = $analysisProcessService->findByWebsite($website->getId());
        }

        if (isset($analysisProcess)) {
            return $this->redirectToDemo($analysisProcess);
        }

        $form = $this->createForm(new DiscoverDomainType(), $website);
        $form->handleRequest($request);

        if ($request->getMethod() == 'POST' && $form->isValid()) {
            $analysisProcess = $analysisProcessService->create();

            $analysisProcess->setType(AnalysisProcess::TYPE_DISCOVER_DEMO);
            $analysisProcess->setStatus(AnalysisProcess::STATUS_READY_TO_PROCESS);

            $website->addAnalysisProcess($analysisProcess);

            $websiteService->save($website);
            $analysisProcessService->save($analysisProcess);

            return $this->redirectToDemo($analysisProcess);
        }

        return array(
            'form' => $form->createView(),
        );
    }

    protected function redirectToDemo(AnalysisProcess $analysisProcess)
    {
        return $this->redirect(
            $this->generateUrl('demo_results', array('analysisProcess' => $analysisProcess->getId()))
        );
    }

    /**
     * Analysis results presentation.
     *
     * @Route("/{analysisProcess}/results", name="demo_results")
     * @param AnalysisProcess $analysisProcess
     * @Template()
     * @return array
     */
    public function resultsAction(Request $request, AnalysisProcess $analysisProcess)
    {
        if ($analysisProcess->getType() != AnalysisProcess::TYPE_DISCOVER_DEMO) {
            throw new \LogicException('Invalid analysis type');
        }

        $website = $analysisProcess->getWebsite();

        return array(
            'analysisProcess' => $analysisProcess,
            'website' => $website,
        );
    }

    /**
     * Run flow of the fast analysis.
     *
     * @param AnalysisProcess $analysisProcess
     * @Route("/{analysisProcess}/run-analysis", name="demo_run_analysis")
     * @return array
     */
    public function runAnalysisAction(Request $request, AnalysisProcess $analysisProcess)
    {
        $status = true;

        if ($analysisProcess->getType() != AnalysisProcess::TYPE_DISCOVER_DEMO) {
            throw new \LogicException('Invalid analysis type');
        }

        if ($analysisProcess->getStatus() != AnalysisProcess::STATUS_READY_TO_PROCESS) {
            throw new \LogicException('Invalid analysis status');
        }

        $website = $analysisProcess->getWebsite();
        $counters = $this->get('analysis_process_counters_factory')->get($analysisProcess);

        try {
            $urls = $this->get('backlinks_moz')->getLinks($website->getName(), 10);
            $validator = $this->get('validator');

            foreach ($urls as $url) {
                $violations = $validator->validateValue($url, array(new NotBlank(), new Url()));

                if (count($violations) === 0) {
                    //$this->get('demo_link_analysis')->analyzeUrl($analysisProcess, $url);

                    $this->get('old_sound_rabbit_mq.demo_link_analysis_producer')->publish(
                        serialize(array(
                            'analysisProcessId' => $analysisProcess->getId(),
                            'url' => $url,
                        )),
                        null,
                        array(),
                        $this->container->getParameter('queue_demo_link_analysis_parallelism')
                    );

                    $counters->toProcess($url);
                }
            }

            $analysisProcess->setStatus(AnalysisProcess::STATUS_PROCESSING);

            $this->get('analysis_processes')->save($analysisProcess);
        } catch (\Exception $e) {
            $status = false;
        }

        return new JsonResponse(array(
            'status' => $status
        ));
    }
}
