<?php

namespace WebDNA\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 * @package WebDNA\Bundle\UserBundle\Controller
 *
 * @Route("/")
 */
class SecurityController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->get('router')->generate('user_workbench_dashboard_index'));
        }
        return parent::loginAction($request);
    }

    /**
     * @Route("/logout_success", name="logout_success")
     * @Template()
     */
    public function logoutSuccessAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->get('router')->generate('user_workbench_dashboard_index'));
        }

        return [];
    }
}
