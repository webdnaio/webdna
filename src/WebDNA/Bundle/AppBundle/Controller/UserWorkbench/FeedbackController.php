<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zendesk\API\Client as ZendeskAPI;

use WebDNA\Bundle\AppBundle\Form\Type\FeedbackType;

/**
 * Class FeedbackController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/feedback")
 */
class FeedbackController extends BaseController
{
    /**
     * @Route("", name="feedback_form")
     */
    public function formAction(Request $request)
    {
        $sent = false;

        if ($request->isMethod('POST')) {
            $userManager = $this->get('users');
            $user = $userManager->find($this->get('security.token_storage')->getToken()->getUser());

            $message = $user->getFirstname()
                . ' <' . $user->getEmail() . '>'
                . ' gave us a feedback:'
                . "\n\n"
                . strip_tags($request->get('message'))
                . "\n-- \n";

            if (mb_strlen($message) > 2) {
                $mailerService = $this->get('mailer');

                $email= $mailerService->createMessage()
                    ->setSubject('WebDNA.io user feedback')
                    ->setFrom(
                        [
                            $user->getEmail()
                            => $user->getFirstname()
                        ]
                    )
                    ->setTo('hello@webdna.io')
                    ->setBody($message);

                if ($mailerService->send($email)) {
                    $sent = true;
                } else {
                    $sent = false;
                }
            }
        }

        return new JsonResponse(['sent' => $sent]);
    }
}
