<?php

namespace WebDNA\Bundle\AppBundle\Iterator;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ValidatorInterface;

class UrlsFilterIterator extends \FilterIterator
{
    private $validator;

    public function __construct(\Iterator $iterator, ValidatorInterface $validator)
    {
        parent::__construct($iterator);

        $this->validator = $validator;
    }

    public function accept()
    {
        $violations = $this->validator->validate(trim($this->current()), array(new NotBlank(), new Url()));

        if (count($violations) === 0) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function count()
    {
        return iterator_count($this);
    }
}
