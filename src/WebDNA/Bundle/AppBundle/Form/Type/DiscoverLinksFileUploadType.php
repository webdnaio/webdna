<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DiscoverLinksFileUploadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('links', 'file', array(
            'required' => true,
            'label' => 'Upload your link list from Google Webmaster Tools (CSV, or TXT)',
            'constraints' => array(
                new File(array(
                    'maxSize' => '10M',
                    'mimeTypes' => array('csv', 'txt', 'text/plain'),
                )),
            ),
            'attr' => array(
                'id' => 'links_file',
                'class' => 'form-control',
                'placeholder' => 'Enter your links...',
            ),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'discover_file_upload_links';
    }
}
