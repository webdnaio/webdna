<?php

namespace WebDNA\Bundle\VerifierBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebDNA\Bundle\UserBundle\Entity\User;
use WebDNA\Bundle\VerifierBundle\Entity\Page;
use WebDNA\Bundle\VerifierBundle\Entity\Queue;
use WebDNA\Bundle\VerifierBundle\Entity\Rating;
use WebDNA\Bundle\VerifierBundle\Entity\Website;

class VerifierLoadDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('verifier:load-data')
            ->setDescription('Load data from file into verifier')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Path to file with URL addresses to verify'
            )
            ->addArgument(
                'users',
                InputArgument::REQUIRED,
                'Usernames involved in verify process'
            )
            ->addOption(
                'website',
                null,
                InputOption::VALUE_OPTIONAL,
                'Website name'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $pageService = $container->get('verifier_pages');
        $queueService = $container->get('verifier_queues');
        $ratingService = $container->get('verifier_ratings');

        $users = $this->getUsers(array_unique(explode(',', $input->getArgument('users'))));
        $website = $input->getOption('website') ? $this->getWebsite($input->getOption('website')) : null;

        $handler = fopen($input->getArgument('file'), 'r');

        if ($handler) {
            while (($url = fgets($handler, 4096)) !== false) {
                $url = trim($url);

                $page = $pageService->findByUrl($url);

                if (!($page instanceof Page)) {
                    // Create new page.
                    $page = $pageService->create();

                    if ($website instanceof Website) {
                        $page->setWebsiteId($website->getId());
                    }

                    $page->setUrl($url);
                    $page->setCreatedAt(new \DateTime());

                    $pageService->save($page);
                }

                // Insert page into users queues.
                foreach ($users as $user) {
                    $rating = $ratingService->find(array('pageId' => $page->getId(), 'userId' => $user->getId()));
                    $queueItem = $queueService->find(array('pageId' => $page->getId(), 'userId' => $user->getId()));

                    if (!($queueItem instanceof Queue) &&
                        !($rating instanceof Rating)) {
                        $queueItem = $queueService->create();

                        $queueItem->setUserId($user->getId());
                        $queueItem->setPageId($page->getId());

                        $queueService->save($queueItem);
                    }
                    $container->get('doctrine')->getManager('verifier')->flush();
                    $container->get('doctrine')->getManager('verifier')->clear();
                }
            }

            fclose($handler);
        }
    }

    /**
     * @param $usernames
     * @return array of User
     */
    protected function getUsers($usernames)
    {
        $usersService = $this->getContainer()->get('users');

        $users = array();

        foreach ($usernames as $username) {
            $user = $usersService->findByUsername($username);

            if (!($user instanceof User)) {
                throw new \LogicException('User ' . $username . ' doesn\'t exist');
            }

            $users[] = $user;
        }

        return $users;
    }

    /**
     * @param $name
     * @return Website
     */
    protected function getWebsite($name)
    {
        $websiteService = $this->getContainer()->get('verifier_websites');
        $website = $websiteService->findByName($name);

        if (!($website instanceof Website)) {
            $website = $websiteService->create();

            $website->setName($name);

            $websiteService->save($website);
        }

        return $website;
    }
}
