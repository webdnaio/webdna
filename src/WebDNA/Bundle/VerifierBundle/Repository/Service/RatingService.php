<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Service;

use WebDNA\Bundle\VerifierBundle\Entity\Rating;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\RatingRepositoryInterface;

/**
 * Class RatingService
 * @package WebDNA\Bundle\VerifierBundle\Repository\Service
 */
class RatingService
{
    /**
     * @var \WebDNA\Bundle\VerifierBundle\Repository\Interfaces\RatingRepositoryInterface
     */
    protected $ratingRepository;

    /**
     * Constructor.
     *
     * @param RatingRepositoryInterface $RatingRepository
     */
    public function __construct(RatingRepositoryInterface $RatingRepository)
    {
        $this->ratingRepository = $RatingRepository;
    }

    /**
     * @return Rating
     */
    public function create()
    {
        return new Rating();
    }

    /**
     * @param $id
     * @return Rating
     */
    public function find($id)
    {
        return $this->ratingRepository->find($id);
    }

    /**
     * @param Rating $Rating
     * @return mixed
     */
    public function save(Rating $Rating)
    {
        return $this->ratingRepository->save($Rating);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->ratingRepository->countAll();
    }
}
