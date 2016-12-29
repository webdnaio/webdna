<?php

namespace WebDNA\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('firstName', 'text');
        $builder->add('lastName', 'text');
        $builder->add('emailNotificationsEnabled', 'checkbox', ['required' => false]);
        // $builder->add('plainPassword', 'password');
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'webdna_user_profile';
    }
}
