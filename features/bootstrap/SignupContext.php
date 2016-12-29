<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Mink\Mink;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
use Behat\Symfony2Extension\Driver\KernelDriver;
use \PHPUnit_Framework_Assert as Assert;

/**
 * Behat context class.
 */
class SignupContext implements SnippetAcceptingContext, MinkAwareContext
{
    private $mink;
    private $minkParameters;
    private $entityManager;
    private $userManager;
    private $email;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     * @param EntityManager $entityManager
     * @param UserManager $userManager
     */
    public function __construct(EntityManager $entityManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase()
    {
        // $purger = new ORMPurger($this->entityManager);
        // $purger->purge();
    }

    /**
     * @Given I am an interested user with email :arg1
     * @param $email
     */
    public function anInterestedUserWithEmail($email)
    {
        // raname user if exists
        $renamed_username = str_replace('@', '_' . time() . '@', $email);

        $this->email = $email;

        $user = $this->userManager->findUserByUsernameOrEmail($email);
        if ($user) {
            $user->setEmail($renamed_username);
            $user->setUsername($renamed_username);
            $user->setEmailCanonical($renamed_username);
            $user->setUsernameCanonical($renamed_username);
            $this->userManager->updateUser($user);
        }
    }

    /**
     * @When I confirm my registration
     */
    public function heConfirmsTheRegistration()
    {
        $user = $this->userManager->findUserByUsernameOrEmail($this->email);

        $client = $this->mink->getSession()->getDriver()->getClient();
        $client->followRedirects(false);

        $this->mink->getSession()->visit('/register/confirm/' . $user->getConfirmationToken());
    }

    /**
     * @Then I should receive the registration email
     */
    public function heShouldReceiveTheRegistrationEmail()
    {

        $profile = $this->getSymfonyProfile();

        $collector = $profile->getCollector('swiftmailer');
        Assert::assertCount(1, $collector->getMessages());
    }

    public function getSymfonyProfile()
    {
        $driver = $this->mink->getSession()->getDriver();
        if (!$driver instanceof KernelDriver) {
            throw new \Behat\Mink\Exception\UnsupportedDriverActionException(
                'You need to tag the scenario with ' .
                '"@mink:symfony2". Using the profiler is not ' .
                'supported by %s',
                $driver
            );
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException(
                'The profiler is disabled. Activate it by setting ' .
                'framework.profiler.only_exceptions to false in ' .
                'your config'
            );
        }

        return $profile;
    }
}
