<?php

namespace WebDNA\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('firstName', 'text');
        // $builder->add('lastName', 'text');
        $builder->add('plainPassword', 'password');
        $builder->add(
            'termsOfUseAgreement',
            'checkbox',
            [
                'label' => false,
                'required' => true,
                'mapped' => false,
            ]
        );
        $builder->add(
            'g-recaptcha-response',
            new RecaptchaType(),
            [
                'label' => false,
                'mapped' => false,
            ]
        );
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'webdna_user_registration';
    }
}
