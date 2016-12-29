<?php

namespace WebDNA\Bundle\UserBundle\OAuthProvider;

use WebDNA\Bundle\UserBundle\OAuthProvider;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class OAuthService
 * @package WebDNA\Bundle\UserBundle\OAuthProvider
 */
class OAuthService
{
    protected $container;
    protected $request;

    /**
     * @param $container
     * @param RequestStack $requestStack
     */
    public function __construct($container, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function create($name)
    {
        $class_name = 'WebDNA\Bundle\UserBundle\OAuthProvider\\' . ucfirst($name);

        if (!class_exists($class_name)) {
            throw new AuthenticationServiceException();
        }

        return new $class_name(array(
            'clientId' => $this->container->getParameter('oauth_' . $name . '_client_id'),
            'clientSecret' => $this->container->getParameter('oauth_' . $name . '_secret_key'),
            'redirectUri' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost()
                . $this->container->getParameter('oauth_redirect_uri') . '/' . $name,
            'scopes' => explode(',', $this->container->getParameter('oauth_' . $name . '_scope')),
        ));
    }
}
