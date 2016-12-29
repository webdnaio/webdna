<?php

namespace WebDNA\Bundle\CommonBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

abstract class LoadDataAbstractFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    protected function loadObjects(ObjectManager $manager, $className)
    {
        foreach ($this->getData($manager) as $name => $data) {
            $entity = new $className();

            // Set all simple fields first.
            foreach ($data as $field => $value) {
                $setterMethod = 'set' . str_replace('_', '', ucwords($field));
                
                if (!is_array($value)) {
                    $entity->$setterMethod($value);
                    
                    continue;
                }
                
                if (is_array($value)) {
                    continue;
                }

                if (!array_key_exists('type', $value)) {
                    throw new Exception('Something goes wrong.');
                }
            }

            $manager->persist($entity);
            $manager->flush();

            $this->addReference($name, $entity);
            
            // Set more complicated fields as arrays of objects.
            foreach ($data as $field => $value) {
                $adderMethod = 'add' . str_replace('_', '', ucwords($field));

                if (is_array($value)) {
                    if (method_exists($entity, $adderMethod)) {
                        foreach ($value as $valueObject) {
                            $entity->$adderMethod($valueObject);
                        }
                        
                        continue;
                    }
                }
            }

            $manager->persist($entity);
            $manager->flush();
        }
    }
}
