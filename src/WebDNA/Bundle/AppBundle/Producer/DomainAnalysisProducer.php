<?php

namespace WebDNA\Bundle\AppBundle\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class DomainAnalysisProducer
 * @package WebDNA\Bundle\AppBundle\Producer
 */
class DomainAnalysisProducer extends Producer
{
    /**
     * {@inheritdoc}
     */
    public function publish($msgBody, $routingKey = '', $additionalProperties = array())
    {
        parent::publish($msgBody, $routingKey, $additionalProperties);
    }
}
