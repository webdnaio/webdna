<?php

namespace WebDNA\Bundle\AppBundle\Controller\AdminWorkbench;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Class DashboardController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/admin")
 */
class DashboardController extends Controller
{

    /**
     * @Route("/", name="admin_workbench_dashboard_index")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @param Request $request
     * @Route("/websites", name="admin_workbench_websites")
     * @Template()
     * @return array
     */
    public function websitesAction(Request $request)
    {
        // Paginator
        $limit = 100;
        $pageNumber = (int)$request->query->get('page', 1);

        $websites = $this->get('websites')->findAll($pageNumber, $limit);

        return [
            'websites' => $websites,
        ];
    }

    /**
     * @param Request $request
     * @Route("/analyzes", name="admin_workbench_analyzes")
     * @Template()
     * @return array
     */
    public function analyzesAction(Request $request)
    {
        // Paginator
        $limit = 100;
        $pageNumber = (int)$request->query->get('page', 1);

        $analyzes = $this->get('analysis_processes')->findAll($pageNumber, $limit);

        return [
            'analyzes' => $analyzes,
        ];
    }

    /**
     * @Route("/users", name="admin_workbench_users")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function usersAction(Request $request)
    {
        // Paginator
        $limit = 100;
        $pageNumber = (int)$request->query->get('page', 1);

        $users = $this->get('users')->findAll($pageNumber, $limit);

        return [
            'users' => $users,
        ];
    }

    /**
     * @Route("/counters", name="admin_workbench_dashboard_counters_xhr", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function countersAction(Request $request)
    {
        $ids = $request->get('ids');
        return new JsonResponse($this->getCounters($ids));
    }

    /**
     * @Route("/set-user-premium-role/{user}/{grant}",
     * name="admin_workbench_dashboard_set_user_premium_xhr", options={"expose"=true}
     * )
     * @param User $user
     * @param int $grant
     * @param Request $request
     * @return JsonResponse
     */
    public function setUserPremiumAction(Request $request, User $user, $grant)
    {
        $manipulator = $this->get('fos_user.util.user_manipulator');

        if ($grant == 1) {
            if ($user->isEnabled()===false) {
                $manipulator->activate($user->getUsername());
            }
            $manipulator->addRole($user->getUsername(), 'ROLE_PREMIUM');
        } else {
            $manipulator->removeRole($user->getUsername(), 'ROLE_PREMIUM');
        }

        return new JsonResponse(['result' => 1]);
    }


    /**
     * @param array $analyses_ids
     * @return array|null
     */
    protected function getCounters(array $analyses_ids)
    {
        $analysisCounters = array();
        $analysisProcessService = $this->get('analysis_processes');

        foreach ($analyses_ids as $analysis_id) {
            $analysisProcess = $analysisProcessService->find($analysis_id);

            $counters = $this->container->get('analysis_process_counters_factory')
                ->get($analysisProcess);

            $analysisCounters[$analysisProcess->getId()] = [
                'count' => $counters->count(),
                'countToProcess' => $counters->countToProcess(),
                'countFailed' => $counters->countFailed(),
                'countProcessed' => $counters->countProcessed(),
            ];
        }

        return $analysisCounters;
    }
}
