<?php

namespace WebDNA\Bundle\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsDomainValidator extends ConstraintValidator
{
    /**
     *
     */
    const PATTERN ='~^
        ([\pL\pN\pS-]+\.)+[\pL]+
        $~ixu';

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match(static::PATTERN, $value, $matches)) {
            $this->context->buildViolation('test')
            ->setParameter('%string%', $value)
            ->addViolation();
        }
    }
}
