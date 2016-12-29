<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Base\Page;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\PageReview;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\PageReviewRepositoryInterface;

/**
 * Class PageReviewService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class PageReviewService
{
    /**
     * @var PageReviewRepositoryInterface
     */
    protected $pageReviewRepository;

    /**
     * Constructor.
     *
     * @param PageReviewRepositoryInterface $pageReviewRepository
     */
    public function __construct(PageReviewRepositoryInterface $pageReviewRepository)
    {
        $this->pageReviewRepository = $pageReviewRepository;
    }

    /**
     * @return PageReview
     */
    public function create()
    {
        return new PageReview();
    }

    /**
     * @param $id
     * @return PageReview
     */
    public function find($id)
    {
        return $this->pageReviewRepository->find($id);
    }

    /**
     * @param PageReview $pageReview
     * @return mixed
     */
    public function save(PageReview $pageReview)
    {
        return $this->pageReviewRepository->save($pageReview);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->pageReviewRepository->countAll();
    }

    /**
     * @param PageReview $pageReview
     * @return mixed
     */
    public function remove(PageReview $pageReview)
    {
        return $this->pageReviewRepository->remove($pageReview);
    }

    /**
     * @param Page $page
     * @param Website $website
     * @return mixed
     */
    public function findByPageAndWebsite(Page $page, Website $website)
    {
        return $this->pageReviewRepository->findByPageAndWebsite($page, $website);
    }

    /**
     * @param Page[] $pages
     * @param AnalysisProcess $analysisProcess
     * @param integer $reviewValue
     */
    public function markAsReviewed(array $pages, AnalysisProcess $analysisProcess, $reviewValue = 1)
    {
        foreach ($pages as $page) {
            $pageReview = $this->findByPageAndWebsite($page, $analysisProcess->getWebsite());

            if (!($pageReview instanceof PageReview)) {
                $pageReview = $this->create();

                $pageReview->setPage($page);
                $pageReview->setWebsite($analysisProcess->getWebsite());
                $pageReview->setAnalysisProcess($analysisProcess);

                $this->save($pageReview);
            } else {
                if ($reviewValue == 0) {
                    $this->remove($pageReview);
                }
            }
        }
    }
}
