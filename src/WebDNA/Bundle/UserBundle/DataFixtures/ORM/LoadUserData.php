<?php

namespace WebDNA\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use WebDNA\Bundle\CommonBundle\DataFixtures\LoadDataAbstractFixture;
use WebDNA\Bundle\UserBundle\Entity\User;

class LoadUserData extends LoadDataAbstractFixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadObjects($manager, 'WebDNA\Bundle\UserBundle\Entity\User');
    }

    public function getData(ObjectManager $manager)
    {
        return array(
            'user_admin' => array(
                'username' => 'admin@webdna.local',
                'account_type' => User::ACCOUNT_TYPE_USER,
                'email' => 'admin@webdna.local',
                'plain_password' => 'm8D1d4nAxnQ9wFdM',
                'enabled' => true,
            ),
            'user_system' => array(
                'username' => 'system@webdna.local',
                'account_type' => User::ACCOUNT_TYPE_SYSTEM,
                'email' => 'system@webdna.local',
                'plain_password' => 'test',
                'enabled' => true,
            ),
            'user_anonymous' => array(
                'username' => 'anonymous@webdna.local',
                'account_type' => User::ACCOUNT_TYPE_ANONYMOUS,
                'email' => 'anonymous@webdna.local',
                'plain_password' => '4Sx3D5Kds435N3kB',
                'enabled' => false,
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
