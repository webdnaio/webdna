<?php

namespace WebDNA\Bundle\AppBundle\Model;

use Predis\Client;

/**
 * Class ProcessingLockFactory
 * @package WebDNA\Bundle\AppBundle\Model
 */
class ProcessingLockFactory
{
    /**
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param $name
     * @return ProcessingLock
     */
    public function get($name)
    {
        return new ProcessingLock($this->redis, $name);
    }
}
