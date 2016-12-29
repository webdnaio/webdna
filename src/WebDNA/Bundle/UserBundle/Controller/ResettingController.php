<?php

namespace WebDNA\Bundle\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ResettingController
 * @package WebDNA\Bundle\UserBundle\Controller
 *
 * @Route("/resetting")
 */
class ResettingController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/success", name="resetting_success")
     */
    public function successAction(Request $request)
    {
        $request->getSession()->getFlashBag()->add(
            'notice',
            'Hurray! Your password has been changed!'
        );
        return $this->redirectToRoute('fos_user_security_login');
    }
}
