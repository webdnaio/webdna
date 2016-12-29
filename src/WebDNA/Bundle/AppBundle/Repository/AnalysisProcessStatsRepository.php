<?php

namespace WebDNA\Bundle\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessStats;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessStatsRepositoryInterface;

/**
 * AnalysisProcessStatsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnalysisProcessStatsRepository extends EntityRepository implements AnalysisProcessStatsRepositoryInterface
{

    /**
     * @param AnalysisProcessStats $analysisProcessStats
     * @return mixed|void
     */
    public function save(AnalysisProcessStats $analysisProcessStats)
    {
        $this->_em->persist($analysisProcessStats);

        return $this->_em->flush();
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function findByAnalysisProcess(AnalysisProcess $analysisProcess)
    {
        return $this->findOneBy(array('analysisProcess' => $analysisProcess));
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(ap.id) FROM WebDNAAppBundle:AnalysisProcessStats ap')
            ->getSingleScalarResult();
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSummary(AnalysisProcess $analysisProcess)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('
            p.negative, p.positive, p.suspicious, p.malware,
            p.analyzedDomains, p.linksFound,
            p.linksFound, p.linksFollow, p.linksNofollow, p.totalPagesAnalyzed,
            p.userClassifiedUrls, p.userUnclassifiedUrls
            ')
            ->from('WebDNAAppBundle:AnalysisProcessStats', 'p')
            ->where('p.analysisProcess = :id')
            ->setParameter('id', $analysisProcess->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
