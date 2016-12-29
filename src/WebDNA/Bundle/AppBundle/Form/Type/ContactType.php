<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Your name *',
            ),
            'constraints' => array(
                new NotBlank(),
            ),
        ));
        $builder->add('email', 'text', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Your email *',
            ),
            'constraints' => array(
                new NotBlank(),
                new Email(),
            ),
        ));
        $builder->add('email_confirmation', 'hidden', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Email confirmation *',
            ),
            'constraints' => array(
                new Blank(),
            ),
        ));
        $builder->add('message', 'textarea', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'rows' => 8,
                'placeholder' => 'Your message *',
            ),
            'constraints' => array(
                new NotBlank(),
            ),
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}
