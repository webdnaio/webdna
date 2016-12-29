<?php

namespace WebDNA\Bundle\AppBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class DomainAnalysisConsumer
 * @package WebDNA\Bundle\AppBundle\Consumer
 */
class DomainAnalysisConsumer implements ConsumerInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        return true;
    }
}
