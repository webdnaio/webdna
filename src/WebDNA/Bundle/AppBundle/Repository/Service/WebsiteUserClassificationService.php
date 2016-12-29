<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\WebsiteUserClassification;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteUserClassificationRepositoryInterface;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Class WebsiteUserClassificationService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class WebsiteUserClassificationService
{
    /**
     * @var WebsiteUserClassificationRepositoryInterface
     */
    protected $websiteUserClassificationRepository;

    /**
     * Constructor.
     *
     * @param WebsiteUserClassificationRepositoryInterface $websiteUserClassificationRepository
     */
    public function __construct(WebsiteUserClassificationRepositoryInterface $websiteUserClassificationRepository)
    {
        $this->websiteUserClassificationRepository = $websiteUserClassificationRepository;
    }

    /**
     * @return WebsiteUserClassification
     */
    public function create()
    {
        return new WebsiteUserClassification();
    }

    /**
     * @param $id
     * @return WebsiteUserClassification
     */
    public function find($id)
    {
        return $this->websiteUserClassificationRepository->find($id);
    }

    /**
     * @param Website $website
     * @param User $user
     * @return WebsiteUserClassification
     */
    public function findUserWebsiteClassification(Website $website, User $user)
    {
        return $this->websiteUserClassificationRepository->findUserWebsiteClassification($website, $user);
    }

    /**
     * @param User $user
     * @param int $class
     * @return mixed
     */
    public function findClassifiedWebsites(User $user, $class = ItemAnalysis::CLASS_NEGATIVE)
    {
        return $this->websiteUserClassificationRepository->findClassifiedWebsites($user, $class);
    }

    /**
     * @param WebsiteUserClassification $websiteUserClassification
     * @return mixed
     */
    public function save(WebsiteUserClassification $websiteUserClassification)
    {
        return $this->websiteUserClassificationRepository->save($websiteUserClassification);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->websiteUserClassificationRepository->countAll();
    }

    /**
     * @param WebsiteUserClassification $websiteUserClassification
     * @return mixed
     */
    public function remove(WebsiteUserClassification $websiteUserClassification)
    {
        return $this->websiteUserClassificationRepository->remove($websiteUserClassification);
    }
}
