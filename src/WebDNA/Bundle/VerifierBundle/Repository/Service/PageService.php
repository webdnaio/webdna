<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Service;

use WebDNA\Bundle\VerifierBundle\Entity\Page;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\PageRepositoryInterface;

/**
 * Class PageService
 * @package WebDNA\Bundle\VerifierBundle\Repository\Service
 */
class PageService
{
    /**
     * @var \WebDNA\Bundle\VerifierBundle\Repository\Interfaces\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * Constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return Page
     */
    public function create()
    {
        return new Page();
    }

    /**
     * @param $id
     * @return Page
     */
    public function find($id)
    {
        return $this->pageRepository->find($id);
    }

    /**
     * @param $url
     * @return mixed
     */
    public function findByUrl($url)
    {
        return $this->pageRepository->findByUrl($url);
    }

    /**
     * @param Page $page
     * @return mixed
     */
    public function save(Page $page)
    {
        return $this->pageRepository->save($page);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->pageRepository->countAll();
    }

    /**
     * Finds unclassified pages
     *
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function findUnclassified($offset = 0, $limit = 50)
    {
        return $this->pageRepository->getUnclassified($offset, $limit);
    }
}
