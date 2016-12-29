<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Interfaces;

use WebDNA\Bundle\VerifierBundle\Entity\Rating;

/**
 * Interface RatingRepositoryInterface
 * @package WebDNA\Bundle\VerifierBundle\Repository\Interfaces
 */
interface RatingRepositoryInterface
{
    /**
     * @param $id
     * @return Rating
     */
    public function find($id);

    /**
     * @param Rating $Rating
     * @return mixed
     */
    public function save(Rating $Rating);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
