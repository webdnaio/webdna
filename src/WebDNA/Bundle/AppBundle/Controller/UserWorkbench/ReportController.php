<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ReportController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/dashboard/report")
 */
class ReportController extends BaseController
{

    /**
     * @Route("/id/{website}/{analysisProcess}/{reportType}",
     * name="user_workbench_report_table", options={"expose"=true})
     * @param Request $request
     * @param Website $website
     * @param null|AnalysisProcess $analysisProcess
     * @param string $reportType
     * @return array
     */
    public function indexAction(
        Request $request,
        Website $website,
        AnalysisProcess $analysisProcess = null,
        $reportType = null
    ) {
        // Default search criteria
        $criteria = $this->setCriteria($request, $website);

        // Report view type
        $reportType = $this->setReportType($reportType);

        if ($request->get('criteria') !== null) {
            return $this->redirect(
                $this->generateUrl(
                    'user_workbench_report_table',
                    [
                        'website' => $website->getId(),
                        'analysisProcess' => $analysisProcess->getId()
                    ]
                )
            );
        }

        $analysisProcessService = $this->get('analysis_processes');

        if (is_null($analysisProcess)) {
            $analysisProcess = $analysisProcessService->findOneByWebsite($website->getId());
        }

        if (!($analysisProcess instanceof AnalysisProcess)) {
            throw $this->createNotFoundException();
        }

        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $limit = 50;
        $pageItemAnalysisNumber = (int)$request->query->get('page', 1);

        $itemAnalyzesService = $this->get('item_analyzes');

        if ($request->get('export') == 1) {
            return $this->export($website, $analysisProcess, $criteria, $pageItemAnalysisNumber, $limit);
        }
        $requestSort = ['column' => $request->get('column'), 'order' => $request->get('order')];

        if (strlen($requestSort['column']) > 2 && strlen($requestSort['order']) >= 3) {
            $sort = $requestSort;
        } elseif ($reportType == 'expert') {
            $sort = ['column' => 'pointing_links', 'order' => 'desc'];
        } else {
            $sort = null;
        }

        $pageItemAnalysisItemAnalyzes = $itemAnalyzesService
            ->findByAnalysisProcess($analysisProcess, $criteria, $pageItemAnalysisNumber, $limit, $sort);

        $itemMetricsService = $this->get('item_metrics');
        $itemMetrics = $itemMetricsService->getMetricsArray($pageItemAnalysisItemAnalyzes->getItems());

        // Statistics
        $analysisProcessSummary = $this->get('analysis_processes_stats')->getSummary($analysisProcess);

        // grouped website object
        if (isset($criteria['website'])) {
            $websiteService = $this->get('websites');
            $groupedWebsite = $websiteService->find($criteria['website']);

            $websiteUserClassificationsService = $this->get('website_user_classifications');
            $currentWebsiteClassification = $websiteUserClassificationsService
                ->findUserWebsiteClassification($groupedWebsite, $this->getUser());
        } else {
            $groupedWebsite = null;
        }

        if (isset($currentWebsiteClassification) && is_object($currentWebsiteClassification)) {
            $currentClass = $currentWebsiteClassification->getClass();
        } else {
            $currentClass = null;
        }

        $itemAnalyzesCount = $itemAnalyzesService
            ->countByAnalysisProcess($analysisProcess);

        $itemAnalyzesReviewCount = $itemAnalyzesService
            ->countReviewedByAnalysisProcess($analysisProcess);

        if ($reportType == 'expert') {
            $template = 'indexExpert';
        } else {
            $template = 'index';
        }

        return new Response(
            $this->renderView(
                'WebDNAAppBundle:UserWorkbench/Report:' . $template . '.html.twig',
                [
                    'sort' => $sort,
                    'criteria' => $criteria,
                    'totalCount' => $itemAnalyzesCount,
                    'reviewedCount' => $itemAnalyzesReviewCount,
                    'allPagesReviewed' => ($itemAnalyzesReviewCount === $itemAnalyzesCount),
                    'analysisProcess' => $analysisProcess,
                    'itemMetrics' => $itemMetrics,
                    'pageItemAnalyzes' => $pageItemAnalysisItemAnalyzes,
                    'analysisProcessSummary' => $analysisProcessSummary,
                    'analysisProcessCount' => $analysisProcessService->countProcessesByWebsite($website->getId()),
                    'isAnyPendingAnalysis' => $analysisProcessService->isAnyPendingByWebsite($website->getId()),
                    'processList' => $this->getProcessList($website),
                    'website' => $website,
                    'groupedWebsite' => $groupedWebsite,
                    'currentClass' => $currentClass,
                    'reportType' => $reportType,
                    'idsSelected' => $this->get('session')->get('selected_items_' . $analysisProcess->getId()),
                    'websiteGroups' => $this->get('item_analyzes')->getWebsiteGroups($analysisProcess),
                    'hideSummary' => $request->cookies->get('hide_summary_' . $analysisProcess->getId())
                ]
            )
        );
    }

    /**
     * @Route("/website-groups/{website}/{analysisProcess}", name="user_workbench_website_groups")
     * @param Request $request
     * @param Website $website
     * @param null|AnalysisProcess $analysisProcess
     * @Template()
     * @return array
     */
    public function websiteGroupsAction(Request $request, Website $website, AnalysisProcess $analysisProcess = null)
    {
        $limit = 50;
        $pageItemAnalysisNumber = (int)$request->query->get('page', 1);
        $criteria = [];

        $analysisProcessService = $this->get('analysis_processes');

        $itemAnalyzesService = $this->get('item_analyzes');
        if (is_null($analysisProcess)) {
            $analysisProcess = $analysisProcessService->findOneByWebsite($website->getId());
        }

        if (!($analysisProcess instanceof AnalysisProcess)) {
            throw $this->createNotFoundException();
        }

        $requestSort = ['column' => $request->get('column'), 'order' => $request->get('order')];

        if (strlen($requestSort['column']) > 2 && strlen($requestSort['order']) >= 3) {
            $sort = $requestSort;
        } else {
            $sort = null;
        }

        $pageItemAnalysisItemAnalyzes = $itemAnalyzesService
            ->findByAnalysisProcessWebsiteGroup(
                $analysisProcess,
                $criteria,
                $pageItemAnalysisNumber,
                $limit,
                $sort,
                true
            );

        // Statistics
        $analysisProcessSummary = $this->get('analysis_processes_stats')->getSummary($analysisProcess);

        return [
            'criteria' => $criteria,
            'analysisProcess' => $analysisProcess,
            'website' => $website,
            'pageItemAnalyzes' => $pageItemAnalysisItemAnalyzes,
            'analysisProcessSummary' => $analysisProcessSummary,
            'analysisProcessCount' => $analysisProcessService->countProcessesByWebsite($website->getId()),
            'isAnyPendingAnalysis' => $analysisProcessService->isAnyPendingByWebsite($website->getId()),
            'processList' => $this->getProcessList($website),
            // @TODO - rewrite it to param converter
            'reportType' => 'domain',
        ];
    }

    /**
     * @Route("/website-groups-xhr/{analysisProcess}/json",
     * name="user_workbench_website_groups_xhr", options={"expose"=true})
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @return array
     */
    public function websitesGroupsListAction(Request $request, AnalysisProcess $analysisProcess)
    {
        if (!($analysisProcess instanceof AnalysisProcess)) {
            throw $this->createNotFoundException();
        }

        if (false === $this->get('security.authorization_checker')->isGranted('view', $analysisProcess)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $pageItemAnalysisItemAnalyzes = $this->get('item_analyzes')->getWebsiteGroups($analysisProcess);

        return new JsonResponse(
            [
                'pageItemAnalyzes' => $pageItemAnalysisItemAnalyzes->getItems(),
                'totalItemCount' => $pageItemAnalysisItemAnalyzes->getTotalItemCount(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param Website $website
     * @return array
     */
    protected function setCriteria(Request $request, Website $website)
    {
        $session = $this->get('session');

        // Class filter
        $criteria = $request->get('criteria', $session->get('criteria_w' . $website->getId()));

        // Resets only class filter
        if (is_array($criteria)) {
            foreach ($criteria as $criteria_key => $criteria_value) {
                if ($criteria_value == 'reset' || strlen($criteria_value) == 0) {
                    unset($criteria[$criteria_key]);
                }
            }
        }

        // Class key must exists
        if (!isset($criteria['class'])) {
            $criteria['class'] = null;
        }

        // Resets all filters, class key must exists
        if (isset($criteria['reset']) && $criteria['reset'] == '1') {
            $criteria = ['class' => null];
        } elseif (count($criteria) > 1) {
            $criteria['active'] = true;
        }

        if ($request->get('criteria') !== null) {
            $session->set('criteria_w' . $website->getId(), $criteria);
        }

        // default search criteria
        $criteria['type'] = ItemAnalysis::TYPE_PAGE;

        if (!isset($criteria['reviewed'])) {
            $criteria['reviewed'] = 0;
        }

        return $criteria;
    }

    /**
     * @param string $reportType
     * @return mixed|string
     */
    protected function setReportType($reportType)
    {
        $session = $this->get('session');

        if ($reportType === null && $session->get('reportType') !== null) {
            return $session->get('reportType');
        } elseif ($reportType === null && $this->get('security.authorization_checker')->isGranted('ROLE_PREMIUM')) {
            return $reportType = 'expert';
        } elseif ($reportType === null) {
            return $reportType = 'url';
        } else {
            if ($reportType == 'expert' && !$this->get('security.authorization_checker')->isGranted('ROLE_PREMIUM')) {
                $reportType = 'url';
            }

            $session->set('reportType', $reportType);

            return $reportType;
        }
    }

    /**
     * @param Website $website
     * @param AnalysisProcess $analysisProcess
     * @param array $criteria
     * @param int $pageItemAnalysisNumber
     * @param int $limit
     * @return StreamedResponse
     */
    protected function export(
        Website $website,
        AnalysisProcess $analysisProcess,
        array $criteria,
        $pageItemAnalysisNumber,
        $limit
    ) {
        $itemAnalyzesService = $this->get('item_analyzes');
        $twig = $this->get('twig.page_class_extension');

        $pageItemAnalysisItemAnalyzes = $itemAnalyzesService
            ->findByAnalysisProcessQuery($analysisProcess, $criteria, $pageItemAnalysisNumber, $limit, true);

        $response = new StreamedResponse(
            function () use ($pageItemAnalysisItemAnalyzes, $analysisProcess, $twig) {
                $results = $pageItemAnalysisItemAnalyzes->iterate();
                $handle = fopen('php://output', 'r+');

                $i = 0;
                $delimeter = ';';
                fputcsv($handle, ['URL', 'Alert type'], $delimeter);
                while (false !== ($row = $results->next())) {
                    fputcsv($handle, [$row[$i]['url'], $twig->nameFilter($row[$i]['class'])], $delimeter);
                    $i++;
                }

                fclose($handle);
            }
        );

        $d = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $website->getName() . '_' . $analysisProcess->getFinished()->format("Y-m-d") . '_' . hash(
                'crc32',
                serialize($criteria)
            ) . '.csv'
        );

        $response->headers->set('Content-Disposition', $d);

        return $response;
    }

    /**
     * @Route("/processes_list", name="user_workbench_report_processes_list_xhr")
     * @Method({"POST"})
     * @param $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getProcessesAction(Request $request)
    {
        $websiteId = (int)$request->get('websiteId');

        $website = $this->get('websites')->find($websiteId);

        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $response = new JsonResponse();
        $response->setData($this->getProcessList($website));

        return $response;
    }

    /**
     * @Route("/details.{type}", name="user_workbench_report_details_xhr", options={"expose"=true})
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function detailsAction(Request $request, $type)
    {
        $itemId = (int)$request->get('itemId');

        $itemAnalysisService = $this->get('item_analyzes');
        $pagesService = $this->get('pages');
        $itemAnalysis = $itemAnalysisService->find($itemId);
        $page = $pagesService->find($itemAnalysis->getObjectId());

        if (!$page) {
            throw new NotFoundResourceException();
        }

        if (false === $this->get('security.authorization_checker')
                ->isGranted(
                    'view',
                    $itemAnalysis->getAnalysisProcess()
                )
        ) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $pageItemMetrics = $this->get('item_metrics')->getMetricsByItemAnalysis($itemAnalysis);

        $twigItemMetricsExtService = $this->get('twig.item_metrics_extension');

        $metrics = [
            ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC,
            ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC,
            ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC,
            ItemMetric::TYPE_URL_PERFORMANCE_METRIC,
            ItemMetric::TYPE_URL_SECURITY_METRIC,
            ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC,
        ];

        $pageItemMetricsFiltered = $twigItemMetricsExtService
            ->getMetrics(
                $pageItemMetrics[$itemAnalysis->getId()],
                $metrics
            );

        ksort($pageItemMetricsFiltered);

        $website = $page->getWebsite();
        $websiteItemMetrics = $this->get('item_metrics')
            ->getMetricsByItemAnalysis($website->getItemAnalysis());

        $websiteItemMetricsFiltered = $twigItemMetricsExtService
            ->getMetrics(
                $websiteItemMetrics[$website->getItemAnalysis()->getId()],
                [
                    ItemMetric::TYPE_DOMAIN_MOZ_METRIC,
                    ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC,
                ]
            );

        $data = [
            'showRestrictedData' => $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'),
            'itemAnalysis' => $itemAnalysis,
            'pageItemMetrics' => $pageItemMetricsFiltered,
            'websiteItemMetrics' => $websiteItemMetricsFiltered,
            'page' => $pagesService->findDetails(
                $page,
                $itemAnalysis->getAnalysisProcess()->getWebsite()
            ),
        ];

        if ($type == 'html') {
            $response = new Response(
                $this->render('WebDNAAppBundle:UserWorkbench/Report/partials/table:details.html.twig', $data)
            );
        } else {
            $response = new JsonResponse();
            $response->setData($data);
        }

        return $response;
    }

    /**
     * @Route("/previous_analysis/{website}", name="user_workbench_report_table_previous_analysis_redirect")
     * @param Request $request
     * @param Website $website
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function previousRedirectAction(Request $request, Website $website)
    {
        $analysisProcess = $this->get('analysis_processes')->findOneByWebsiteAndStatus(
            $website->getId(),
            AnalysisProcess::STATUS_COMPLETED
        );

        return $this->redirect(
            $this->generateUrl(
                'user_workbench_report_table',
                [
                    'website' => $website->getId(),
                    'analysisProcess' => $analysisProcess->getId()
                ]
            )
        );
    }

    /**
     * @Route("/mark-as-reviewed/{analysisProcess}/{page}",
     * name="user_workbench_report_mask_as_reviewed", options={"expose"=true}
     * )
     * @Method("post")
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @param int $page
     * @return Response
     */
    public function markAsReviewed(Request $request, AnalysisProcess $analysisProcess, $page = 0)
    {
        if (!($analysisProcess instanceof AnalysisProcess)) {
            throw $this->createNotFoundException();
        }

        if (false === $this->get('security.authorization_checker')->isGranted('view', $analysisProcess)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $idsDecoded = json_decode($request->get('page_ids'));

        $pages = $this->get('pages')->getPagesByIds($idsDecoded);

        if (is_array($pages)) {
            $this->get('page_reviews')->markAsReviewed($pages, $analysisProcess);
        }

        return new Response();
    }

    /**
     * @Route("/session-mark-as-reviewed/{analysisProcess}/{reviewValue}",
     * name="user_workbench_report_session_mark_as_reviewed")
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @param integer $reviewValue  1 - mask as review, 0 - remove review mark
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sessionMarkAsReviewed(Request $request, AnalysisProcess $analysisProcess, $reviewValue = 1)
    {
        $ids = $this->get('session')->get('selected_items_' . $analysisProcess->getId());

        if (!empty($ids)) {
            $pages = $this->get('item_analyzes')->getPagesIdsFromItemsIds($ids);
            // $pages = $this->get('pages')->getPagesByIds($pagesIds);
            $this->get('page_reviews')->markAsReviewed($pages, $analysisProcess, $reviewValue);
            $this->get('session')->remove('selected_items_' . $analysisProcess->getId());
        }

        return $this->redirectToRoute(
            'user_workbench_report_table',
            [
                'analysisProcess' => $analysisProcess->getId(),
                'website' => $analysisProcess->getWebsite()->getId()
            ]
        );
    }

    /**
     * @Route("/set-items-selected/{analysisProcess}",
     * name="user_workbench_report_set_items_selected", options={"expose"=true}
     * )
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @return Response
     */
    public function setItemsAsSelected(Request $request, AnalysisProcess $analysisProcess)
    {
        if (!($analysisProcess instanceof AnalysisProcess)) {
            throw $this->createNotFoundException();
        }

        if (false === $this->get('security.authorization_checker')->isGranted('view', $analysisProcess)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $idsDecoded = json_decode($request->get('item_ids'));
        $markSelected = $request->get('mark_selected');

        if (!empty($idsDecoded)) {
            $session = $this->get('session');
            $idsStored = $session->get('selected_items_' . $analysisProcess->getId());

            if (!empty($idsStored)) {
                if ($markSelected == 'select') {
                    $ids = array_merge($idsStored, $idsDecoded);
                } else {
                    $ids = array_filter(
                        $idsStored,
                        function ($v) use ($idsDecoded) {
                            if (in_array($v, $idsDecoded)) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    );
                }
            } else {
                if ($markSelected == 'select') {
                    $ids = $idsDecoded;
                }
            }

            $session->set('selected_items_' . $analysisProcess->getId(), array_unique($ids));
        }

        return new Response();
    }
}
