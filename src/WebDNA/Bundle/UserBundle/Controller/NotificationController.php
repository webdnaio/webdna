<?php

namespace WebDNA\Bundle\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NotificationController extends BaseController
{
    /**
     * @Route("/notifications", name="user_profile_notifications")
     * @param Request $request
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createFormBuilder($user)
            ->add('emailNotificationsEnabled', 'checkbox', ['required' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);
            $submitSuccess = true;
        } else {
            $submitSuccess = false;
        }

        return $this->render(
            'WebDNAUserBundle:Profile:notification.html.twig',
            array(
                'form' => $form->createView(),
                'submitSuccess' => $submitSuccess,
            )
        );
    }
}
