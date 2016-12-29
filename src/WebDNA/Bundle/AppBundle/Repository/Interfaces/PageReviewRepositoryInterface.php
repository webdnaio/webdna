<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Base\Page;
use WebDNA\Bundle\AppBundle\Entity\PageReview;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Interface PageReviewRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface PageReviewRepositoryInterface
{
    /**
     * @param $id
     * @return PageReview
     */
    public function find($id);

    /**
     * @param PageReview $pageReview
     * @return mixed
     */
    public function save(PageReview $pageReview);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param PageReview $pageReview
     * @return mixed
     */
    public function remove(PageReview $pageReview);

    /**
     * @param array $ids
     * @param Website $website
     * @return mixed
     */
    public function markAsReviewed(array $ids, Website $website);

    /**
     * @param Page $page
     * @param Website $website
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByPageAndWebsite(Page $page, Website $website);
}
