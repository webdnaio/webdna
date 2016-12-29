<?php

namespace WebDNA\Bundle\AppBundle\Provider;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class LinkAnalysisQueueProvider
 * @package WebDNA\Bundle\AppBundle\Model
 */
class LinkAnalysisQueueProvider extends AnalysisQueueProvider
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
            'name' => 'link_analysis_' . $number,
            'callback' => function (AMQPMessage $msg) use ($callback) {
                return $callback->execute($msg);
            },
            'routing_keys' => array(
                'analysis.link.full.' . $number,
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
