<?php

namespace WebDNA\Bundle\AppBundle\Model;

use Predis\Client;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisProcessCounters
 * @package WebDNA\Bundle\AppBundle\Model
 */
class AnalysisProcessCounters
{
    /**
     *
     */
    const STATUS_TO_PROCESS = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_FAILED = 3;

    /**
     * @param Client $redis
     * @param AnalysisProcess $analysisProcess
     */
    public function __construct(Client $redis, AnalysisProcess $analysisProcess)
    {
        $this->redis = $redis;
        $this->analysisProcess = $analysisProcess;
    }

    /**
     * @param $url
     * @return $this
     */
    public function toProcess($url)
    {
        $this->redis->zadd($this->buildKey(), self::STATUS_TO_PROCESS, $url);

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function failed($url)
    {
        $this->redis->zadd($this->buildKey(), self::STATUS_FAILED, $url);

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function processed($url)
    {
        $this->redis->zadd($this->buildKey(), self::STATUS_PROCESSED, $url);

        return $this;
    }

    /**
     * @return mixed
     */
    public function countToProcess()
    {
        return $this->redis->zcount($this->buildKey(), self::STATUS_TO_PROCESS, self::STATUS_TO_PROCESS);
    }

    /**
     * @return mixed
     */
    public function countFailed()
    {
        return $this->redis->zcount($this->buildKey(), self::STATUS_FAILED, self::STATUS_FAILED);
    }

    /**
     * @return mixed
     */
    public function countProcessed()
    {
        return $this->redis->zcount($this->buildKey(), self::STATUS_PROCESSED, self::STATUS_PROCESSED);
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->redis->zcount($this->buildKey(), '-inf', '+inf');
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        $total = $this->count();

        return $total > 0 && ($total === $this->countProcessed() + $this->countFailed());
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
        return 'counters:analysis-process:' . $this->analysisProcess->getId();
    }
}
