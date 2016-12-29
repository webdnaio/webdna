<?php

namespace WebDNA\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class RecaptchaType extends AbstractType
{
    public function getName()
    {
        return 'webdna_recaptcha';
    }
}
