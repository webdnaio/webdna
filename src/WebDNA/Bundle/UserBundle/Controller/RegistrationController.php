<?php

namespace WebDNA\Bundle\UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController
 * @package WebDNA\Bundle\UserBundle\Controller
 *
 * @Route("/")
 */
class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /*
        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        $captcha_error = false;

        if (
            $request->isMethod('POST') === true
            && $this->container->getParameter('recaptcha.enabled') === true
        ) {
            $recaptcha = $this->get('webdna_recaptcha');
            $resp = $recaptcha->verify($request->get('g-recaptcha-response'), $request->getClientIp());
            if (!$resp->isSuccess()) {
                $errors = $resp->getErrorCodes();
                foreach ($errors as $error) {
                    $form->addError(new FormError($error));
                }
                $captcha_error = true;
            }
        }

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(
                FOSUserEvents::REGISTRATION_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }
        */

        return $this->render(
            'FOSUserBundle:Registration:register.html.twig',
            [
                // 'form' => $form->createView(),
                // 'captcha_error' => $captcha_error,
                //'recaptcha_sitekey' => ($this->container->getParameter('recaptcha.enabled') === true)
                //    ? $this->container->getParameter('recaptcha.sitekey') : null
            ]
        );
    }

    /**
     * @Route("/register_success", name="register_success")
     * @Template()
     */
    public function successAction()
    {
        return [];
    }

    /**
     * @param Request $request
     * @param $token
     * @return RedirectResponse
     */
    public function confirmAction(Request $request, $token)
    {
        try {
            return parent::confirmAction($request, $token);
        } catch (NotFoundHttpException $e) {
            return new RedirectResponse(
                $this->get('router')->generate('user_workbench_dashboard_index')
            );
        }
    }
}
