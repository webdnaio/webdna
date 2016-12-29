<?php

namespace WebDNA\Bundle\AppBundle\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class LinkAnalysisProducer
 * @package WebDNA\Bundle\AppBundle\Producer
 */
class LinkAnalysisProducer extends Producer
{
    /**
     * {@inheritdoc}
     */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array(), $queueCount = 1)
    {
        if (empty($routingKey)) {
            $data = unserialize($msgBody);

            $routingKey = 'analysis.link.full.' . ($data['analysisProcessId'] % $queueCount + 1);
        }

        parent::publish($msgBody, $routingKey, $additionalProperties);
    }
}
