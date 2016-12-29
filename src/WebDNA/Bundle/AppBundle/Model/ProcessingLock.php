<?php

namespace WebDNA\Bundle\AppBundle\Model;

use Predis\Client;

/**
 * Class ProcessingLock
 * @package WebDNA\Bundle\AppBundle\Model
 */
class ProcessingLock
{
    /**
     * @var Client
     */
    protected $redis;

    /**
     * @var
     */
    protected $name;

    /**
     * @param Client $redis
     * @param $name
     */
    public function __construct(Client $redis, $name)
    {
        $this->redis = $redis;
        $this->name = $name;
    }

    /**
     * @param int $ttl
     * @return bool
     */
    public function acquire($ttl = 30)
    {
        $result = $this->redis->setnx($this->buildKey(), time());

        if ($result) {
            $this->redis->expire($this->buildKey(), $ttl);
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->redis->exists($this->buildKey());
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->redis->del($this->buildKey());

        return $this;
    }

    /**
     * @return string
     */
    protected function buildKey()
    {
        return 'locks:' . $this->name;
    }
}
