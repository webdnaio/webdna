<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NotificationsCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class NotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('notifications:send')
            ->setDescription('Sends (email) notifications about completed analyses to users');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start...');

        $notificationEmailsService = $this->getContainer()->get('notification_emails');
        $notificationsService = $this->getContainer()->get('notifications');
        $notifications = $notificationsService->getNotSentNotifications();

        $output->writeln('emails to sent: ' . count($notifications));

        foreach ($notifications as $notification) {
            $notificationEmailsService->sendNotification($notification);
        }

        $output->writeln('Finished');
    }
}
