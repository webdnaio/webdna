<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeDomainQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeUrlQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\FetchUrlQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\GenerateUrlThumbnailQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\GetDNSRecordQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\GetSocialShareCountersQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\GetWhoisDataQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\Parse\CalculateLinkPositionQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\Parse\CalculateTextToHTMLRatioQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\Parse\ParseHTMLLinksQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\Parse\ParseHTMLLinkList;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\Parse\ParseHTMLMetaTagsQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\QueueCommand;

/**
 * Class QueueTestCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class QueueTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:test')
            ->setDescription('Process queue')
            ->addArgument('name', InputArgument::REQUIRED, 'Queue names to process')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Test queue');

        $queue = $this->getContainer()->get('url_analysis_queue');
        $name = $input->getArgument('name');

        //$queue->publish(new AnalyzeDomainQueueCommand($input->getArgument('name')));
        //$queue->publish(new GetWhoisDataQueueCommand($name));
        //$queue->publish(new GenerateUrlThumbnailQueueCommand($name));
        //$queue->publish(new AnalyzeUrlQueueCommand($name));
        //$queue->publish(new ParseHTMLMetaTagsQueueCommand(file_get_contents($name)));
        //$command = new ParseHTMLMetaTagsQueueCommand(file_get_contents($name));
        //$command = new CalculateTextToHTMLRatioQueueCommand(file_get_contents($name));
        //$command = new CalculateLinkPositionQueueCommand(file_get_contents($name), 'nextclick.pl');
        $command = new GetSocialShareCountersQueueCommand($name);

//        $html = <<<EOT
//<html>
//    <head>
//        <title>dsds</title>
//    </head>
//    <body>
//        <a href="http://link1.pl">Text 1</a>
//        <a href="http://link2.pl">
//            <span>Text 2</span>
//        </a>
//        <a href="http://link3.pl">
//            <span>Text 3.1</span>
//            Text 3.2
//            <i>Text 3.3</i>
//        </a>
//        <a href="http://link5.pl">
//            <img src="#" alt="Image 5.1" />
//            <img src="#" alt="Image 5.2" />
//        </a>
//        <a href="http://link6.pl">
//            <img src="#" alt="Image 6.1" />
//            Text 6.2
//        </a>
//    </body>
//</html>
//EOT;
//
//        $command = new ParseHTMLLinksQueueCommand(file_get_contents($name));
//
//        $result = $command->execute();
//
//        var_dump($result);

        //$command = new ParseHTMLLinksQueueCommand(file_get_contents($name));

        $result = $command->execute();
        var_dump($result);

        //$list = $result['parse_html_links:links_list'];

        //var_dump($list->filterDomainLinks('w.polki.pl'));

        $output->writeln('Finished...');
    }
}
