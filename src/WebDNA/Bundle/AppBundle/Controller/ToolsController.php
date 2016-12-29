<?php

namespace WebDNA\Bundle\AppBundle\Controller;

use Buzz\Browser;
use Buzz\Client\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Form\Type\SeoToolType;
use WebDNA\Bundle\AppBundle\Model\Crawler\DOMCrawler;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentHtmlMetaIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentHtmlMetaSocialIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentHtmlTextStructureIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentLinksAttrFollowIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentLinksDistributionIMBuilder;

/**
 * Class SeoToolsController
 * @package WebDNA\Bundle\AppBundle\Controller
 *
 * @Route("/tools")
 */
class ToolsController extends Controller
{
    const TOOL_TEXT_TO_CODE_RATIO = 1;
    const TOOL_LINKS_COUNTER = 2;
    const TOOL_META_ANALYZER= 3;

    /**
     * @Route("/text-to-code-ratio", name="seo_tools_text_to_code_ratio")
     * @Route("/links-counter", name="seo_tools_links_counter")
     * @Route("/meta-analyzer", name="seo_tools_meta_analyzer")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new SeoToolType());
        $type = null;

        switch ($request->get('_route')) {
            case 'seo_tools_text_to_code_ratio':
                $type = self::TOOL_TEXT_TO_CODE_RATIO;
                $header = 'html2text.html.twig';

                break;

            case 'seo_tools_links_counter':
                $type = self::TOOL_LINKS_COUNTER;
                $header = 'linksCounter.html.twig';

                break;
            case 'seo_tools_meta_analyzer':
                $type = self::TOOL_META_ANALYZER;
                $header = 'metaAnalyzer.html.twig';

                break;
            default:
                throw new \LogicException();
        }

        $form->get('type')->setData($type);

        return array(
            'form' => $form->createView(),
            'header' => $header,
        );
    }

    /**
     * @Route("/results", name="seo_tools_results")
     * @Template()
     */
    public function resultsAction(Request $request)
    {
        $form = $this->createForm(new SeoToolType());
        $data = array();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                try {
                    $crawler = $this->getCrawler($data['url']);

                    switch ($data['type']) {
                        case self::TOOL_TEXT_TO_CODE_RATIO:
                            $builder = new DocumentHtmlTextStructureIMBuilder($crawler);

                            $partial = 'html2text.html.twig';
                            $data = array(
                                'metric' => $builder->build(),
                            );

                            break;
                        case self::TOOL_LINKS_COUNTER:
                            $links = $crawler->getLinks();

                            $linksAttrBuilder = new DocumentLinksAttrFollowIMBuilder($links, new Website());
                            $linksDistributionBuilder = new DocumentLinksDistributionIMBuilder($crawler, $links);

                            $partial = 'linksCounter.html.twig';
                            $data = array(
                                'linksAttrMetric' => $linksAttrBuilder->build(),
                                'linksDistributionMetric' => $linksDistributionBuilder->build(),
                            );

                            break;
                        case self::TOOL_META_ANALYZER:
                            $metaBuilder = new DocumentHtmlMetaIMBuilder($crawler);
                            $metaSocialBuilder = new DocumentHtmlMetaSocialIMBuilder($crawler);

                            $partial = 'metaAnalyzer.html.twig';
                            $data = array(
                                'metaMetric' => $metaBuilder->build(),
                                'meteaSocialMetric' => $metaSocialBuilder->build(),
                            );

                            break;
                    }
                } catch (\Exception $e) {
                    $partial = 'invalid.html.twig';
                }
            } else {
                $partial = 'invalid.html.twig';
            }
        } else {
            $this->createNotFoundException();
        }

        return $this->render(
            'WebDNAAppBundle:Tools:results/' . $partial,
            $data
        );
    }

    /**
     *
     */
    protected function getCrawler($url)
    {
        $client = new Curl();

        $client->setTimeout(30);
        $client->setMaxRedirects(10);

        $browser = new Browser($client);

        $response = $browser->get($url);

        $crawler = new DOMCrawler($response->getContent());

        return $crawler;
    }
}
