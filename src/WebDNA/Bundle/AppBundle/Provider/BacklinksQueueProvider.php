<?php

namespace WebDNA\Bundle\AppBundle\Provider;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class BacklinksQueueProvider
 */
class BacklinksQueueProvider extends AnalysisQueueProvider
{
    /**
     * @var
     */
    protected $count;

    /**
     * @param $callback
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
            'name' => 'backlinks_' . $number,
            'callback' => function (AMQPMessage $msg) use ($callback) {
                return $callback->execute($msg);
            },
            'routing_keys' => array(
                'backlinks.link.full.' . $number,
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
