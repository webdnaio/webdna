<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Disavow;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use WebDNA\Bundle\VerifierBundle\Entity\Website;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class DisavowController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/dashboard/disavow")
 */
class DisavowController extends BaseController
{

    /**
     * @Route("/{analysisProcess}", name="user_workbench_disavow_index")
     * @Template()
     * @param AnalysisProcess $analysisProcess
     * @return array
     */
    public function indexAction(AnalysisProcess $analysisProcess)
    {
        $website = $analysisProcess->getWebsite();

        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        // Statistics
        $analysisProcessSummary = $this->get('analysis_processes_stats')->getSummary($analysisProcess);

        $history = $this->get('disavow')->findBy(
            ['analysisProcess' => $analysisProcess],
            ['createdAt' => 'DESC'],
            100,
            0
        );

        return [
            'history' => $history,
            'analysisProcess' => $analysisProcess,
            'analysisProcessSummary' => $analysisProcessSummary,
            'website' => $website,
            'section_id' => 'disavow',
        ];
    }

    /**
     * @Route("/download_page/{analysisProcess}/{type}", name="user_workbench_disavow_download_landing_page")
     * @Template()
     * @param AnalysisProcess $analysisProcess
     * @param int $type
     * @return array
     */
    public function downloadSuccessAction(AnalysisProcess $analysisProcess, $type)
    {
        $website = $analysisProcess->getWebsite();

        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        return [
            'website' => $website,
            'analysisProcess' => $analysisProcess,
            'type' => $type,
            'websites' => $this->getWebsites(),
        ];
    }

    /**
     * @Route("/download/{analysisProcess}", name="user_workbench_disavow_download")
     * @Template()
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @return array
     */
    public function downloadAction(Request $request, AnalysisProcess $analysisProcess)
    {
        $website = $analysisProcess->getWebsite();

        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        // websites (domains) classified by user as negative
        $domains = $this->get('website_user_classifications')->findClassifiedWebsites($this->getUser());

        $excluded_domains = [];
        $excluded_websites_ids = [];

        if (count($domains)) {
            foreach ($domains as $domain) {
                $excluded_domains[] = "domain:" . $domain["name"];
                $excluded_websites_ids[] = $domain["id"];
            }
        }

        $pages = $this->get('pages')->getDisavowUrlsByAnalysisProcess($analysisProcess, $excluded_websites_ids);

        if (empty($pages) && empty($excluded_domains)) {
            throw new \UnexpectedValueException(
                'There are no pages with analysisProcessId: ' . $analysisProcess->getId()
            );
        }

        $urls = array_merge($excluded_domains, array_map('current', $pages));

        $content = implode("\n", $urls);

        $disavowService = $this->get('disavow');
        $disavow = $disavowService->create();
        $disavow->setAnalysisProcess($analysisProcess);
        $disavow->setContent($content);
        $disavowService->save($disavow);

        $this->setResponseHeaders($request, $website->getName(), date("Y-m-d"));

        return $this->render(
            'WebDNAAppBundle:UserWorkbench/Disavow:download.html.twig',
            [
                'urls' => $urls,
                'website' => $website,
                'analysisProcess' => $analysisProcess,
                'websites' => $this->getWebsites(),
            ]
        );
    }

    /**
     * @Route("/{analysisProcess}/{id}/download_history", name="user_workbench_disavow_download_history")
     * @param Request $request
     * @param AnalysisProcess $analysisProcess
     * @param int $id
     * @return array
     */
    public function downloadHistoryAction(Request $request, AnalysisProcess $analysisProcess, $id)
    {
        ini_set('memory_limit', '2G');

        $website = $analysisProcess->getWebsite();

        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $disavow = $this->get('disavow')->findOneBy(['analysisProcess' => $analysisProcess, 'id' => $id]);

        if (empty($analysisProcess)) {
            throw new \UnexpectedValueException(
                'There is no disavow associated with analysisProcessId: '
                . $analysisProcess->getId()
            );
        }

        $this->setResponseHeaders($request, $website->getName(), $disavow->getCreatedAt()->format("Y-m-d_H_i"));

        return new Response($disavow->getContent());
    }

    /**
     * @param Request $request
     * @param string $websiteName
     * @param string|null $dateFileSuffix
     */
    protected function setResponseHeaders(Request &$request, $websiteName, $dateFileSuffix = null)
    {
        $type = $request->get('type', 'txt');

        $response = new Response();

        switch ($type) {
            case 'xls':
                $ext = 'xls';
                $response->headers->set('Content-Type', 'application/vnd.ms-excel');
                break;
            case 'txt':
            default:
                $ext = 'txt';
                $response->headers->set('Content-Type', 'text/plain');
                break;
        }

        $d = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $websiteName . '_' . $dateFileSuffix . '_disavow.' . $ext
        );

        $response->headers->set('Content-Disposition', $d);

        $response->sendHeaders();
    }
}
