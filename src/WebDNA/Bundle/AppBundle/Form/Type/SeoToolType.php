<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class SeoToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', 'text', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Your URL address',
            ),
            'constraints' => array(
                new NotBlank(),
                new Url(),
            ),
        ));
        $builder->add('type', 'hidden', array(
            'label' => false,
            'constraints' => array(
                new NotBlank(),
            ),
        ));
    }

    public function getName()
    {
        return 'seo_tool';
    }
}
