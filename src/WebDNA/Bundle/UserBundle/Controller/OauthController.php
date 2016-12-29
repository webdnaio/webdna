<?php

namespace WebDNA\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use WebDNA\Bundle\UserBundle\Entity\ContactData;

class OauthController extends Controller
{
    /**
     * @Route("/connect/{name}", requirements={"name": "\w+"}, defaults={"name" = null}, name="oauth_connect")
     * @param string $name
     * @return RedirectResponse
     * @throws AuthenticationServiceException
     */
    public function connectAction($name)
    {
        $provider = $this->get('webdna_oauth_client')->create($name);

        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            return $this->redirect($provider->getAuthorizationUrl());
        } else {
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $_GET['code']
                ]
            );

            // Optional: Now you have a token you can look up a users profile data
            try {
                // We got an access token, let's now get the user's details
                $userDetails = $provider->getUserDetails($token);
                $userManager = $this->container->get('fos_user.user_manager');
                $user = $userManager->findUserByEmail($userDetails->email);

                if (is_null($user)) {
                    // Use these details to create a new profile
                    $user = $userManager->createUser();
                    $user->setUsername($userDetails->email);
                    $user->setEmail($userDetails->email);
                    $user->setPassword(
                        password_hash(md5(time() . $userDetails->email . mt_rand(1, 100000)), PASSWORD_DEFAULT)
                    );
                    $user->setEnabled(true);

                    $contactDataService = $this->get('contact_datas');

                    $contactData = $contactDataService->create();
                    $contactData->setType(ContactData::TYPE_FIRST_NAME);
                    $contactData->setStatus(ContactData::STATUS_CONFIRMED);
                    $contactData->setValue($userDetails->firstName);

                    $user->addContactData($contactData);

                    $contactData = $contactDataService->create();
                    $contactData->setType(ContactData::TYPE_LAST_NAME);
                    $contactData->setStatus(ContactData::STATUS_CONFIRMED);
                    $contactData->setValue($userDetails->lastName);

                    $user->addContactData($contactData);

                    $user->setLastLogin(new \DateTime());

                    $userManager->updateUser($user);
                }

                if ($user) {
                    $user->setLastLogin(new \DateTime());
                    $userManager->updateUser($user);

                    $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
                    $context = $this->get('security.token_storage');
                    $context->setToken($token);

                    return $this->redirect($this->generateUrl(
                        'user_workbench_dashboard_index',
                        ['remote_auth' => $name]
                    ));
                } else {
                    return $this->redirect($this->generateUrl('fos_user_registration_register'));
                }
            } catch (\Exception $e) {
                // Failed to get user details
                throw new AuthenticationServiceException();
            }

            // Use this to interact with an API on the users behalf
            // echo $token->accessToken;

            // Use this to get a new access token if the old one expires
            // echo $token->refreshToken;

            // Number of seconds until the access token will expire, and need refreshing
            // echo $token->expires;
        }
    }
}
