<?php

/**
 * Class Rating
 * @package WebDNA\Bundle\VerifierBundle\Form\Type
 */
namespace WebDNA\Bundle\VerifierBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('class', 'hidden', array(
            'attr' => array(
                'value' => '0'
            ),
        ));

        if (is_array($options['data']['buttons'])) {
            foreach ($options['data']['buttons'] as $class_value => $button_name) {
                $builder->add($button_name, 'submit', array(
                    'attr' => array(
                        'value' => $class_value,
                        'form' => 'rating',
                        'onclick' => "jQuery(this).removeAttr('name');jQuery('#rating_class').val(this.value);",
                    ),
                ));
            }
        }

        $builder->add('reasons', 'choice', array(
            'choices' => $options['data']['reasons'] ?: array(),
            'attr' => array(
                'data-placeholder' => 'Select reason...',
            ),
            'empty_value' => 'Choose at least one reason',
            'multiple'  => true,
        ));
        
        $builder->add('all_pages_in_subdomain', 'checkbox', array(
            'label' => 'Classify all pages in subdomain',
            'attr' => array(
                'value' => '1'
            ),
        ));
    }

    public function getName()
    {
        return 'rating';
    }
}
