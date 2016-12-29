<?php

namespace WebDNA\Bundle\AppBundle\Controller;

use PicoFeed\Reader\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use WebDNA\Bundle\AppBundle\WebDNAAppBundle;

/**
 * Class DefaultController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $response = $this->render(
            'WebDNAAppBundle:Default:index.html.twig',
            array(
                'section_id' => 'homepage',
            )
        );

        return $response;
    }

    /**
     * @Route("", name="modules/counters")
     * @Template()
     */
    public function countersAction()
    {
        $response = $this->render(
            'WebDNAAppBundle:Default:counters.html.twig',
            array(
                'pagesCount' => $this->get('pages')->countAll(),
                'websitesCount' => $this->get('websites')->countAll(),
                'itemMetricsCount' => $this->get('item_metrics')->countAll(),
            )
        );

        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/cookies", name="page_cookies_policy")
     */
    public function cookiesAction()
    {
        return $this->redirectToRoute('page_disclaimer');
    }

    /**
     * @Route("/about-us", name="page_about_us")
     */
    public function aboutUsAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:aboutUs.html.twig');

//        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/features", name="page_features")
     */
    public function featuresAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:features.html.twig');

        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/disclaimer", name="page_disclaimer")
     */
    public function disclaimerAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:disclaimer.html.twig');

        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/privacy_policy", name="page_privacy_policy")
     */
    public function privacypolicyAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:privacy_policy.html.twig');

//        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/refund_policy", name="page_refund_policy")
     */
    public function refundpolicyAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:refund_policy.html.twig');

//        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/pricing", name="page_pricing")
     */
    public function pricingAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:pricing.html.twig');

//        $response->setSharedMaxAge(600);

        return $response;
    }

    /**
     * @Route("/faq", name="page_faq")
     */
    public function faqAction()
    {
        $response = $this->render('WebDNAAppBundle:Default:faq.html.twig');

//        $response->setSharedMaxAge(600);

        return $response;
    }
}
