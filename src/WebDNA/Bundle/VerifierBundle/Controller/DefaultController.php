<?php

namespace WebDNA\Bundle\VerifierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use WebDNA\Bundle\VerifierBundle\Form\Type\RatingType;
use WebDNA\Bundle\VerifierBundle\Entity\Page;
use WebDNA\Bundle\VerifierBundle\Entity\Queue;

/**
 * Class VerifyController
 * @package WebDNA\Bundle\VerifierBundle\Controller
 */
class DefaultController extends Controller
{
    const CLASS_POSITIVE = 1;
    const CLASS_NEGATIVE = 2;
    const CLASS_NEUTRAL = 4;

    /**
     * @Route("/link-verification-tool/navbar/{id}", name="link-verification-tool-navbar")
     * @Template()
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public function navbarAction(Request $request, $id)
    {
        $session = $request->getSession();

        $form_params = [];

        $form_params['reasons'] = [
            1 => 'shallow content',
            2 => 'doorway page',
            4 => 'SPAM',
            8 => 'too many links in footer',
            16 => 'page not found',
            32 => 'website errors',
            64 => 'slow loading',
            128 => '404',
            256 => 'web directory',
            512 => 'domain squatting',
            1024 => 'outdated content',
        ];

        $form_params['buttons'] = [
            self::CLASS_POSITIVE => 'class_positive',
            self::CLASS_NEUTRAL => 'class_suspicious',
            self::CLASS_NEGATIVE => 'class_negative',
        ];

        $form = $this->createForm(new RatingType(), $form_params);
        
        return [
            'id'        => $id,
            'page'      => $session->get('page'),
            'form'      => $form->createView(),
            'domain'    => $request->get('domain', null),
            'websiteId'    => $request->get('websiteId', null),
            'domains'   => $this->get('verifier_queues')->getDomains($this->getUser()),
            'websites'   => $this->get('verifier_queues')->getWebsites($this->getUser()),
        ];
    }

    /**
     * @param Request $request
     * @Route("/link-verification-tool", name="link-verification-tool")
     * @Template()
     * @return array
     */
    public function indexAction(Request $request)
    {
        $domain = $request->get('domain', null);
        $website = (int) $request->get('websiteId') ? $this->get('verifier_websites')->find($request->get('websiteId')) : null;

        $page = $this->get('verifier_queues')->get($this->getUser(), $website, $domain);

        $request->getSession()->set('page', $page);

        return [
            'page' => $page,
            'domain'    => $request->get('domain', null),
            'websiteId'    => $request->get('websiteId', null),
        ];
    }

    /**
     * @Route("/link-verification-tool/classify/id/{pageId}", requirements={"pageId" = "\d+", "class" = "\d+"}, name="link-verification-tool-classify")
     * @param Request $request
     * @param integer $pageId       Page id
     *
     * @return array
     */
    public function classifyAction(Request $request, $pageId)
    {
        $ratingValues = $request->get('rating', null);
        $page = $this->get('verifier_pages')->find($pageId);

        if (isset($ratingValues['all_pages_in_subdomain'])) {
            $domain = $page->getDomain();
            $websiteId = $page->getWebsiteId();
            if (is_null($websiteId)) {
                $website = null;
            } else {
                $website = $this->get('verifier_websites')->find($page->getWebsiteId());
            }
            $queue = $this->get('verifier_queues')->getAll($this->getUser(), $website, $domain);
        } else {
            $_queue = $this->get('verifier_queues')->find(array('userId' => $this->getUser()->getId(), 'pageId' => $page->getId()));
            if (!is_null($_queue)) {
                $queue = [$_queue];
            }
        }

        if (!empty($queue)) {
            foreach ($queue as $queueItem) {
                $pageId = $queueItem->getPageId();
                $page = $this->get('verifier_pages')->find($pageId);
                $this->classify($queueItem, $page, $ratingValues);
            }
        }

        return $this->redirect($this->generateUrl('link-verification-tool'));
    }

    /**
     *
     * @param Queue $queueItem
     * @param Page  $page
     * @param array $ratingValues
     */
    protected function classify(Queue $queueItem, Page $page, $ratingValues)
    {
        $rating = $this->get('verifier_ratings')->create();

        $rating->setUserId($this->getUser()->getId());
        $rating->setPageId($page->getId());
        $rating->setClass((int) $ratingValues['class']);

        if (isset($ratingValues['reasons']) === true && is_array($ratingValues['reasons'])) {
            $rating->setReasons(array_sum($ratingValues['reasons']));
        } else {
            $rating->setReasons(0);
        }

        $rating->setCreatedAt(new \DateTime());

        $this->get('verifier_ratings')->save($rating);
        $this->get('verifier_queues')->delete($queueItem);
    }
}
