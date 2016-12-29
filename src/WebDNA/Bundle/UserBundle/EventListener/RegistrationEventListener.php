<?php

namespace WebDNA\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RegistrationEventListener
 * @package WebDNA\Bundle\UserBundle\EventListener
 */
class RegistrationEventListener implements EventSubscriberInterface
{

    /**
     * @var UrlGeneratorInterface
     * @var ContainerInterface
     */
    protected $router;
    protected $container;

    public function __construct(UrlGeneratorInterface $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'success',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'confirmed',
        );
    }

    /**
     * Triggered after account activation is successful
     * @param FormEvent $event
     */
    public function success(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('register_success')));
    }

    public function confirmed(FilterUserResponseEvent $event)
    {
        $template_vars = [
            'user' => $event->getUser(),
            'subject' => 'Congratulations WebDNA.io account activated'
        ];

        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($template_vars['subject'])
            ->setFrom(
                [
                    $this->container->getParameter('sender_email_address')
                    => $this->container->getParameter('sender_name')
                ]
            )
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->container->get('templating')->render(
                    'WebDNAUserBundle:Registration:email_success.html.twig',
                    $template_vars
                ),
                'text/html'
            )
            /*
            ->addPart(
                $this->container->get('templating')->render(
                    'WebDNAUserBundle:Registration:email_success.text.twig',
                    $template_vars
                ),
                'text/plain'
            )
            */
            ;

        $mailer->send($message);
    }
}
