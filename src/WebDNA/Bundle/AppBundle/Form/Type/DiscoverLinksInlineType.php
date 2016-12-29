<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use WebDNA\Bundle\AppBundle\Validator\Constraints\ContainsDomain;

class DiscoverLinksInlineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('links', 'textarea', array(
            'required' => true,
            'label' => 'Or paste backlinks in form below',
            'constraints' => array(
            ),
            'attr' => array(
                'id' => 'links_inline',
                'class' => 'form-control',
                'placeholder' => 'Paste your  backlinks...',
                'rows' => 20,
            )
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'discover_inline_links';
    }
}
