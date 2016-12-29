<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsletterSubscribeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array(
            'label' => false,
            'constraints' => array(
                new NotBlank(array('message' => 'This field is required.')),
                new Email(array('message' => 'Please enter a valid email address.')),
            ),
            'attr' => array(
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'Enter your email address...',
            ),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
