<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Link;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\LinkRepositoryInterface;

/**
 * Class LinkService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class LinkService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\LinkRepositoryInterface
     */
    protected $linkRepository;

    /**
     * Constructor.
     *
     * @param LinkRepositoryInterface $linkRepository
     */
    public function __construct(LinkRepositoryInterface $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * @return Link
     */
    public function create()
    {
        return new Link();
    }

    /**
     * @param $id
     * @return Link
     */
    public function find($id)
    {
        return $this->linkRepository->find($id);
    }

    /**
     * @param Link $link
     * @return mixed
     */
    public function save(Link $link)
    {
        return $this->linkRepository->save($link);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->linkRepository->countAll();
    }
}
