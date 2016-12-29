<?php

namespace WebDNA\Bundle\AppBundle\Provider;

use OldSound\RabbitMqBundle\Provider\QueuesProviderInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AnalysisQueueProvider
 * @package WebDNA\Bundle\AppBundle\Provider
 */
abstract class AnalysisQueueProvider implements QueuesProviderInterface
{
    /**
     * Return array of queues
     *
     * @return array
     */
    public function getQueues()
    {
        $queues = array();

        for ($i = 1; $i <= $this->getQueueCount(); $i++) {
            $params = array_merge(
                $this->getQueueDefaultParams(),
                $this->getQueueParams($i)
            );

            $queues[$params['name']] = $params;
        }

        return $queues;
    }

    /**
     * Return queue default parameters.
     *
     * @return array
     */
    protected function getQueueDefaultParams()
    {
        return array(
            'passive' => false,
            'durable' => true,
            'exclusive' => false,
            'auto_delete' => false,
            'nowait' => false,
            'arguments' => null,
            'ticket' => null,
            'routing_keys' => array(),
        );
    }

    /**
     * Return n-th queue parameters like name, callback, routing-keys, etc.
     * These parameters are merged with defaults.
     *
     * @param $number
     * @return mixed
     */
    abstract protected function getQueueParams($number);

    /**
     * Return number of queues.
     *
     * @return mixed
     */
    abstract protected function getQueueCount();
}
