<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Model\Crawler as Crawler;

/**
 * Class CrawlerCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class CrawlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crawler:test')
            ->setDescription('DOMCrawler test')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'URL, i.e. http://example.com'
            )
            ->addArgument(
                'user-agent',
                InputArgument::OPTIONAL,
                'user-agent'
            )
            ->addOption(
                'links',
                null,
                InputOption::VALUE_NONE,
                'print all links from document'
            )
            ->addOption(
                'text',
                null,
                InputOption::VALUE_NONE,
                'print extracted text'
            )
            ->addOption(
                'title',
                null,
                InputOption::VALUE_NONE,
                'print title'
            );
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        if ($input->hasArgument('user-agent')) {
            $userAgent = $input->getArgument('user-agent');
        } else {
            $userAgent = 'WebDNA/1.0';
        }

        if ($url) {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $content = curl_exec($ch);

            if (!curl_errno($ch)) {
                $crawler = new Crawler\DOMCrawler($content);

                if ($input->getOption('title')) {
                    echo $output->writeln('<info>title: </info>')
                        . $crawler->getMetaTitle() . PHP_EOL;
                }

                if ($input->getOption('text')) {
                    echo $output->writeln('<info>plain text: </info>')
                        . ' ' . $crawler->extractPlainText() . PHP_EOL;
                }

                if ($input->getOption('links')) {
                    try {
                        $this->printMessages($output, $crawler->getLinks());
                    } catch (Exception $e) {
                        $message = "<error>Document doesn't contain any link</error>";
                        $output->writeln($message) . PHP_EOL;
                    }
                }
            }

            curl_close($ch);
        }
    }

    /**
     * Print command messages
     *
     * @param OutputInterface       $output
     * @param Crawler\Link\LinkList $links
     */
    protected function printMessages(OutputInterface $output, Crawler\Link\LinkList $links)
    {
        foreach ($links as $link) {
            echo
                $output->write('<info>[' . $link->getTypeLabel() . '] </info>')
                . ($link->isFollow() === false ? $output->write('<comment>[nofollow]</comment> ') : '')
                . $output->write('<comment>' . $link->getUri() . '</comment>')
                . PHP_EOL . (strlen($link->getText()===0)?'(empty string)':$link->getText()) . ' '
                . PHP_EOL;
        }
    }
}
