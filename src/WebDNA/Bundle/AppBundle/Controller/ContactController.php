<?php

namespace WebDNA\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zendesk\API\Client as ZendeskAPI;

use WebDNA\Bundle\AppBundle\Form\Type\ContactType;

/**
 * Class ContactController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("", name="contact_form")
     * @Template()
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(new ContactType());
        $sent = null;

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $form = $this->createForm(new ContactType());

                $mailerService = $this->get('mailer');

                $email = $mailerService->createMessage()
                    ->setSubject('WebDNA.io contact form')
                    ->setFrom(
                        [
                            $data['email']
                            => $data['name']
                        ]
                    )
                    ->setTo('hello@webdna.io')
                    ->setBody(strip_tags($data['message']));

                if ($mailerService->send($email)) {
                    $sent = true;
                } else {
                    $sent = false;
                }
            }
        }

        return array(
            'form' => $form->createView(),
            'sent' => $sent,
        );
    }
}
