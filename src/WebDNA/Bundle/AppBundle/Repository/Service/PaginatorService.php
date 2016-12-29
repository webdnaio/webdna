<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use Doctrine\ORM\Query;
use Knp\Component\Pager\Paginator;

class PaginatorService
{

    /**
     * @param Paginator $paginator
     */
    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param int $pageNumber
     * @param int $limit
     * @return int
     */
    public function getOffset($pageNumber, $limit)
    {
        return (int)sprintf("%u", ((int)($pageNumber - 1) * $limit));
    }

    /**
     * @param $query
     * @param $pageNumber
     * @param $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|null
     */
    public function getPaginator($query, $pageNumber, $limit)
    {
        if (!($query instanceof Query)) {
            return null;
        } else {
            return $this->paginator->paginate(
                $query,
                $pageNumber,
                $limit,
                ['distinct' => false]
            );
        }
    }
}
