<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Page;

class PageClassifyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('page:classify')
            ->setDescription('Returns unclassified pages in CSV format (format: id;url)')
            ->addArgument(
                'offset',
                InputArgument::REQUIRED,
                'first result offset'
            )
            ->addArgument(
                'limit',
                InputArgument::OPTIONAL,
                'result limit'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $offset = $input->getArgument('offset');
        $limit  = $input->getArgument('limit');

        $table = $this->getHelperSet()->get('table');

        $table->setHeaders(array(
            'Page ID',
            'Page URL',
            'Negative class similarity',
            'Positive class similarity',
            'Classification'
        ));

        $container = $this->getContainer();
        $pageService = $container->get('pages');
        $itemAnalysisService = $container->get('item_analyzes');
        $sybilla = $container->get('sybilla_client');

        $unclassifiedPageItemAnalyzes = $itemAnalysisService->findUnclassified(ItemAnalysis::TYPE_PAGE, $offset, $limit);

        if (!empty($unclassifiedPageItemAnalyzes)) {
            foreach ($unclassifiedPageItemAnalyzes as $pageItemAnalysis) {
                $metrics = new ArrayCollection();

                $page = $pageService->find($pageItemAnalysis->getObjectId());
                //$pageItemAnalysis = $page->getItemAnalysis();

                if ($pageItemAnalysis instanceof ItemAnalysis) {
                    foreach ($pageItemAnalysis->getItemMetrics() as $metric) {
                        $metrics->add($metric);
                    }
                }

                $websiteItemAnalysis = $page->getWebsite()->getItemAnalysis();

                if ($websiteItemAnalysis instanceof ItemAnalysis) {
                    foreach ($websiteItemAnalysis->getItemMetrics() as $metric) {
                        $metrics->add($metric);
                    }
                }

                try {
                    $classification = $sybilla->classify($page, $metrics);

                    $pageItemAnalysis->setClassNegativeSimilarity($classification['negative']);
                    $pageItemAnalysis->setClassPositiveSimilarity($classification['positive']);

                    $class = $pageItemAnalysis->getClassNegativeSimilarity() >= $pageItemAnalysis->getClassPositiveSimilarity()
                        ? ItemAnalysis::CLASS_NEGATIVE
                        : ItemAnalysis::CLASS_POSITIVE;
                } catch (\Exception $e) {
                    $class = ItemAnalysis::CLASS_UNKNOWN;
                    $container->get('logger')->error(sprintf(
                        '[sibilla] Exception during url %s analysis: %s',
                        $page->getUrl(),
                        $e->getMessage()
                    ));
                }

                $pageItemAnalysis->setClassSystem($class);

                $itemAnalysisService->save($pageItemAnalysis);

                $table->addRow(array(
                    $page->getId(),
                    $page->getUrl(),
                    $pageItemAnalysis->getClassNegativeSimilarity(),
                    $pageItemAnalysis->getClassPositiveSimilarity(),
                    ItemAnalysis::$CLASSES[$class],
                ));
            }

            $table->render($output);
        }
    }
}
