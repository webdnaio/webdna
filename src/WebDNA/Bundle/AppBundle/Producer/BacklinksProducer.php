<?php

namespace WebDNA\Bundle\AppBundle\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class BacklinksProducer
 * @package WebDNA\Bundle\AppBundle\Producer
 */
class BacklinksProducer extends Producer
{
    /**
     * @param string $msgBody
     * @param string $routingKey
     * @param array $additionalProperties
     * @param $queueCount
     */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array(), $queueCount = 1)
    {
        if (empty($routingKey)) {
            $data = unserialize($msgBody);

            $routingKey = 'backlinks.link.full.' . ($data['analysisProcessId'] % $queueCount + 1);
        }
        parent::publish($msgBody, $routingKey, $additionalProperties);
    }
}
