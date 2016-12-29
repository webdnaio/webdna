<?php

namespace WebDNA\Bundle\AppBundle\Controller\UserWorkbench;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Class StreamController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/dashboard/stream")
 */
class StreamController extends BaseController
{
    /**
     * @Route("/{website}", name="user_workbench_stream")
     * @Template()
     * @param Website $website
     * @return array
     */
    public function indexAction(Website $website)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('view', $website)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $eventService = $this->get('events');

        $events = $eventService->findbyWebsite($website);

        return array(
            'website' => $website,
            'events' => $events,
        );
    }
}
