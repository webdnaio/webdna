<?php

namespace WebDNA\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DiscoverLinksType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', 'file', array(
            'label' => 'Upload your link list from Google Webmaster Tools (CSV, or TXT)',
            'required' => false,
            'constraints' => array(
                new File(array(
                    'maxSize'=>'10M',
                    'mimeTypes'=>array('csv','txt','text/plain')
                )),
            ),
            'attr' => array(
                'id' => 'links_from_file',
                'class' => 'form-control',
                'placeholder' => 'Enter your links...',
            ),
        ));
        $builder->add('inline', 'textarea', array(
            'label' => 'Or paste backlinks in form below',
            'required' => false,
            'constraints' => array(
            ),
            'attr' => array(
                'id' => 'links_from_paste',
                'class' => 'form-control',
                'placeholder' => 'Paste backlinks...',
                'rows' => 20,
            )
        ));

        // Custom validation rule, require one of declared fields.
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $valid = false;

            foreach (array('file', 'inline') as $fieldName) {
                $data = $form[$fieldName]->getData();

                $valid = $valid || !empty($data);
            }

            if (!$valid) {
                $form['file']->addError(new FormError('Please upload your link list'));
            }
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'discover_links';
    }
}
