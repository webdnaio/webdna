<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\Page;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use WebDNA\Bundle\AppBundle\Entity\WebsiteUserClassification;

/**
 * Class ClassificationController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/dashboard/classify")
 */
class ClassificationController extends BaseController
{

    /**
     * @Route("/website/{website}/{analysisProcess}/{page}", name="user_workbench_website_classification_page")
     * @param Request $request
     * @param Website $website
     * @param AnalysisProcess $analysisProcess
     * @param Page $page
     * @return array
     * @Template()
     */
    public function websiteAction(
        Request $request,
        Website $website,
        AnalysisProcess $analysisProcess,
        Page $page
    ) {
        if (false === $this->get('security.authorization_checker')
                ->isGranted(
                    'view',
                    $analysisProcess
                )
        ) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $analysisProcessService = $this->get('analysis_processes');
        $websiteUserClassificationsService = $this->get('website_user_classifications');

        $groupedWebsite = $page->getWebsite();

        $currentWebsiteClassification = $websiteUserClassificationsService
            ->findUserWebsiteClassification($groupedWebsite, $this->getUser());

        if ($currentWebsiteClassification) {
            $currentClass = $currentWebsiteClassification->getClass();
        } else {
            $currentClass = null;
        }

        return [
            'isAnyPendingAnalysis' => $analysisProcessService->isAnyPendingByWebsite($website->getId()),
            'website' => $website,
            'analysisProcess' => $analysisProcess,
            'groupedWebsite' => $groupedWebsite,
            'currentClass' => $currentClass,
        ];
    }

    /**
     * @Route("/item", name="user_workbench_classify_item_xhr", options={"expose"=true})
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function classifyAction(Request $request)
    {
        $class = (int)$request->get('class');
        $itemId = (int)$request->get('itemId');

        $pageItemAnalysisService = $this->get('item_analyzes');
        $analysisProcessStatsService = $this->container->get('analysis_processes_stats');

        $pageItemAnalysis = $pageItemAnalysisService->find($itemId);

        if (!$pageItemAnalysis) {
            throw new NotFoundResourceException();
        }

        $prevClass = $pageItemAnalysis->getClass();

        $twig = $this->get('twig.page_class_extension');

        $message = $twig->nameFilter($class);
        /* . ' '
        . $twig->changedLabelFunction(['classSystem' => $pageItemAnalysis->getClassSystem(), 'class' => $class]);
        */
        $cssLabel = $twig->cssLabelFilter($class);
        $cssLinkLabel = $twig->changedCssLinkLabelFunction($class);

        $cssIcon = $twig->changedCssIconFilter($class);

        if (false === $this->get('security.authorization_checker')
                ->isGranted(
                    'view',
                    $pageItemAnalysis->getAnalysisProcess()
                )
        ) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $pageItemAnalysis->setClassUser($class);
        $pageItemAnalysisService->save($pageItemAnalysis);

        $analysisProcessStatsService->setStatsClassCounters($pageItemAnalysis->getAnalysisProcess());

        $session = $this->get('session');
        $reportType = $session->get('reportType');

        $response = new JsonResponse();
        $response->setData(
            [
                'prevClass' => $prevClass,
                'message' => (($reportType == 'expert') ? substr($message, 0, 1) : $message),
                'cssLinkLabel' => $cssLinkLabel,
                'cssLabel' => $cssLabel,
                'cssIcon' => $cssIcon,
            ]
        );

        return $response;
    }

    /**
     * @Route("/session-classify/{analysisProcess}/{class}", name="user_workbench_report_session_classify")
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @param integer $class
     * @return RedirectResponse
     */
    public function sessionClassifyAction(Request $request, AnalysisProcess $analysisProcess, $class)
    {
        $ids = $this->get('session')->get('selected_items_' . $analysisProcess->getId());
        $pageItemAnalysisService = $this->get('item_analyzes');
        $em = $this->get('doctrine')->getManager();

        if (!empty($ids)) {
            foreach ($ids as $itemId) {
                $pageItemAnalysis = $pageItemAnalysisService->find($itemId);

                if (false === $this->get('security.authorization_checker')
                        ->isGranted(
                            'view',
                            $pageItemAnalysis->getAnalysisProcess()
                        )
                ) {
                    continue;
                }

                $pageItemAnalysis->setClassUser($class);
                $pageItemAnalysisService->save($pageItemAnalysis);

                $em->flush();
                $em->clear();
            }
        }

        $analysisProcessStatsService = $this->container->get('analysis_processes_stats');
        $analysisProcessStatsService->setStatsClassCounters($analysisProcess);

        $this->get('session')->remove('selected_items_' . $analysisProcess->getId());

        return $this->redirectToRoute(
            'user_workbench_report_table',
            [
                'analysisProcess' => $analysisProcess->getId(),
                'website' => $analysisProcess->getWebsite()->getId()
            ]
        );
    }

    /**
     * @Route("/website", name="user_workbench_report_classify_website")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function classifyWebsiteAction(Request $request)
    {
        $class = (int)$request->get('class');
        $websiteId = (int)$request->get('website_id');
        $groupedWebsiteId = (int)$request->get('grouped_website_id');
        $analysisProcessId = (int)$request->get('analysis_process_id');

        $websitesService = $this->get('websites');
        $analysisProcessesService = $this->get('analysis_processes');
        $websiteUserClassificationsService = $this->get('website_user_classifications');
        $itemAnalyzesService = $this->get('item_analyzes');
        $analysisProcessStatsService = $this->container->get('analysis_processes_stats');

        $website = $websitesService->find($websiteId);
        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        if (!$website) {
            throw new NotFoundResourceException();
        }

        $analysisProcess = $analysisProcessesService->find($analysisProcessId);

        if (false === $this->get('security.authorization_checker')
                ->isGranted(
                    'view',
                    $analysisProcess
                )
        ) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $groupedWebsite = $websitesService->find($groupedWebsiteId);

        $websiteUserClassification = $websiteUserClassificationsService
            ->findUserWebsiteClassification($groupedWebsite, $this->getUser());

        if (!$websiteUserClassification instanceof WebsiteUserClassification) {
            $websiteUserClassification = $websiteUserClassificationsService->create();
        }

        if ($request->get('revert') > 0 && $websiteUserClassification instanceof WebsiteUserClassification) {
            $websiteUserClassificationsService->remove($websiteUserClassification);
            $itemAnalyzesService->revertDefaultWebsiteClass($analysisProcess, $groupedWebsite);
        } else {
            $websiteUserClassification->setWebsite($groupedWebsite);
            $websiteUserClassification->setClass($class);
            $websiteUserClassification->setUser($this->getUser());
            $websiteUserClassificationsService->save($websiteUserClassification);

            $itemAnalyzesService->setWebsiteClassUser($analysisProcess, $groupedWebsite, $class);
        }

        $analysisProcessStatsService->setStatsClassCounters($analysisProcess);

        $request->getSession()->getFlashBag()->add(
            'notice',
            'All urls from ' . $groupedWebsite->getName() . ' domain have been set to ' . ItemAnalysis::$CLASSES[$class]
        );

        return $this->redirectToRoute(
            'user_workbench_report_table',
            [
                'website' => $website->getId(),
                'analysisProcess' => $analysisProcess->getId(),
            ]
        );
    }
}
