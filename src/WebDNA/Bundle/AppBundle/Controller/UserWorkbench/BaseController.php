<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebDNA\Bundle\AppBundle\Entity\Base\Website;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{

    /**
     * Get a simple list of processes
     * @param Website $website
     * @param int $limit
     * @return array
     */
    protected function getProcessList(Website $website, $limit = 4)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $processes = $this->get('analysis_processes')->findProcessesByWebsite($website->getId(), $limit);

        $analysisProcessesList = [];

        foreach ($processes as $process) {
            $route = $this->generateUrl(
                'user_workbench_report_table',
                [
                    'website' => $website->getId(),
                    'analysisProcess' => $process['id'],
                ]
            );
            $process['created'] = $process['created']->format('Y-m-d H:i');
            $process['status_label'] = AnalysisProcess::$statusLabels[$process['status']];
            $process['route'] = $route;

            $analysisProcessesList[] = $process;
        }

        return $analysisProcessesList;
    }

    /**
     * @param int $pageNumber
     * @param int $limit
     * @param Website $website
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    protected function getWebsites($pageNumber = 1, $limit = 1000, Website $website = null)
    {
        return $this->get('websites')->findUserWebsites($this->getUser(), $pageNumber, $limit, $website);
    }
}
