<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use WebDNA\Bundle\AppBundle\Validator\Constraints\ContainsDomain;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiscoverDomainType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'label' => 'Domain',
            'constraints' => [
                new NotBlank(['message' => 'This field is required.']),
                new ContainsDomain()
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Enter your domain, like example.com',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'discover_domain';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['domain'],
        ]);
    }
}
