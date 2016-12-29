<?php

namespace WebDNA\Bundle\AppBundle\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class InternalLinkAnalysisProducer
 * @package WebDNA\Bundle\AppBundle\Producer
 */
class InternalLinkAnalysisProducer extends Producer
{
    /**
     * {@inheritdoc}
     */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array(), $queueCount = 1)
    {
        if (empty($routingKey)) {
            $data = unserialize($msgBody);

            $routingKey = 'analysis.link.internal.' . ($data['analysisProcessId'] % $queueCount + 1);
        }

        parent::publish($msgBody, $routingKey, $additionalProperties);
    }
}
