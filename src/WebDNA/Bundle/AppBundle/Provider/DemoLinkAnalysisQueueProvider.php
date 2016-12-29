<?php

namespace WebDNA\Bundle\AppBundle\Provider;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class DemoLinkAnalysisQueueProvider
 * @package WebDNA\Bundle\AppBundle\Model
 */
class DemoLinkAnalysisQueueProvider extends AnalysisQueueProvider
{
    /**
     * @var
     */
    protected $count;

    /**
     * @param $count
     */
    public function __construct($callback, $count)
    {
        $this->callback = $callback;
        $this->count = $count;
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueueParams($number)
    {
        $callback = $this->callback;

        return array(
            'name' => 'demo_link_analysis_' . $number,
            'callback' => function (AMQPMessage $msg) use ($callback) {
                return $callback->execute($msg);
            },
            'routing_keys' => array(
                'analysis.link.demo.' . $number,
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueueCount()
    {
        return $this->count;
    }
}
