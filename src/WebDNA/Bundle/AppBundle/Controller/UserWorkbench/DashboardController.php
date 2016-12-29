<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Class DashboardController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/dashboard")
 */
class DashboardController extends BaseController
{

    /**
     * @Route("/", name="user_workbench_dashboard_index")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $limit = 25;
        $pageNumber = (int)$request->query->get('page', 1);
        $websites = $this->getWebsites($pageNumber, $limit);

        if ($websites->getTotalItemCount() === 0) {
            return $this->redirectToRoute('user_workbench_website_add');
        }

        return [
            'websites_list' => $websites,
            'analysisCounters' => $this->getCounters($websites->getItems()),
        ];
    }

    /**
     * @Route("/websites.json/{website}", name="user_workbench_dashboard_data_xhr", options={"expose"=true})
     * @param Request $request
     * @param Website $website
     * @return JsonResponse
     */
    public function websitesJsonDataAction(Request $request, Website $website = null)
    {
        $limit = 1000;

        $pageNumber = (int) $request->query->get('page', 1);
        $websites = $this->getWebsites($pageNumber, $limit, $website);

        $websiteItems = $websites->getItems();

        return new JsonResponse(
            [
                'items' => $websiteItems,
                'counters' => $this->getCounters($websiteItems, true),
                'statusLabels' => AnalysisProcess::$statusLabels
            ]
        );
    }

    /**
     * @Route("/counters", name="user_workbench_dashboard_counters_xhr")
     * @return JsonResponse
     */
    public function countersAction()
    {
        $websites = $this->getWebsites();
        return new JsonResponse($this->getCounters($websites->getItems(), true));
    }

    /**
     * @Route("/counter/{analysisProcess}", name="user_workbench_dashboard_ap_counter_xhr", options={"expose"=true})
     * @param AnalysisProcess $analysisProcess
     * @return \WebDNA\Bundle\AppBundle\Model\AnalysisProcessCounters
     */
    public function getCounterByAnalysisProcess(AnalysisProcess $analysisProcess)
    {
        $counters = $this->container->get('analysis_process_counters_factory')
            ->get($analysisProcess);
        return new JsonResponse([
            'count' => $counters->count(),
            'countToProcess' => $counters->countToProcess(),
            'countFailed' => $counters->countFailed(),
            'countProcessed' => $counters->countProcessed(),
        ]);
    }

    /**
     * @param Website[] $websites
     * @param bool $flat
     * @return array|null
     */
    protected function getCounters(array $websites, $flat = false)
    {
        $analysisCounters = array();
        foreach ($websites as $website) {
            $analysisProcess = $website['analysisProcess'];

            $counters = $this->container->get('analysis_process_counters_factory')
                ->get($analysisProcess);

            if ($flat === true) {
                $analysisCounters[$analysisProcess->getId()] = [
                    'count' => $counters->count(),
                    'countToProcess' => $counters->countToProcess(),
                    'countFailed' => $counters->countFailed(),
                    'countProcessed' => $counters->countProcessed(),
                ];
            } else {
                $analysisCounters[$analysisProcess->getId()] = $counters;
            }
        }
        return $analysisCounters;
    }
}
