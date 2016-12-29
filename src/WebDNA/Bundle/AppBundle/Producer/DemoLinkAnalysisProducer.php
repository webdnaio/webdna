<?php

namespace WebDNA\Bundle\AppBundle\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class DemoLinkAnalysisProducer
 * @package WebDNA\Bundle\AppBundle\Producer
 */
class DemoLinkAnalysisProducer extends Producer
{
    /**
     * {@inheritdoc}
     */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array(), $queueCount = 1)
    {
        if (empty($routingKey)) {
            $data = unserialize($msgBody);

            $routingKey = 'analysis.link.demo.' . ($data['analysisProcessId'] % $queueCount + 1);
        }

        parent::publish($msgBody, $routingKey, $additionalProperties);
    }
}
