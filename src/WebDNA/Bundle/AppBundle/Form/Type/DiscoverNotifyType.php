<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class DiscoverNotifyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text', array(
            'label' => 'E-mail address',
            'constraints' => array(
                new NotBlank(array('message' => 'This field is required.')),
                new Email(),
            ),
            'attr' => array(
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'Enter your e-mail address',
            )
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'discover_notify';
    }
}
