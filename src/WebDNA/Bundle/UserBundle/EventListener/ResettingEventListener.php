<?php

namespace WebDNA\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class ResettingEventListener implements EventSubscriberInterface
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

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'success',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'completed',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function success(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('resetting_success')));
    }

    public function completed(FilterUserResponseEvent $event)
    {
        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject('WebDNA.io password recovery success')
            ->setFrom([
                $this->container->getParameter('sender_email_address')
                => $this->container->getParameter('sender_name')
            ])
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->container->get('templating')->render(
                    'WebDNAUserBundle:Resetting:email_success.html.twig',
                    ['user' => $event->getUser()]
                ),
                'text/html'
            );

        $mailer->send($message);
    }
}
