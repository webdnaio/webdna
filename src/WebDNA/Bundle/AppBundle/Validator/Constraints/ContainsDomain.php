<?php

namespace WebDNA\Bundle\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsDomain extends Constraint
{
    public $message = 'The string "%string% is not domain';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
