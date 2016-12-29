<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use WebDNA\Bundle\AppBundle\Entity\Page;

class ClassExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('class_name', array($this, 'getClassName')),
        );
    }

    /**
     * @param $object
     * @return string
     */
    public function getClassName($object, $namespaced = true)
    {
        $className = get_class($object);

        if (!$namespaced && $pos = strrpos($className, '\\')) {
            $className = substr($className, $pos + 1);
        }

        return $className;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'class';
    }
}
