<?php

namespace WebDNA\Bundle\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Renderer\ListRenderer;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class Builder
 * @package WebDNA\Bundle\AppBundle\Menu
 */
class Builder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     */
    public function topMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Top menu');

        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild('CONTACT US', array(
            'route' => 'contact_form',
        ));

        $menu->addChild('HOME', array(
           'route' => 'homepage',
        ));

        $menu->addChild('ABOUT', array(
           'route' => 'page_about_us' ,
        ));

        $menu->addChild('BLOG', array(
            'uri' => 'http://blog.webdna.io/',
            'linkAttributes' => array(
                'target' => '_blank',
            ),
        ));

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->addChild('SIGN OUT', array(
                'route' => 'fos_user_security_logout',
            ));
            $menu->addChild('DASHBOARD', array(
                'route' => 'user_workbench_dashboard_index',
            ));
        } else {
            $menu->addChild('SIGN IN', array(
                'route' => 'fos_user_security_login',
            ));

            /*
            $menu->addChild('REGISTER', array(
                'route' => 'fos_user_registration_register',
            ));
            */
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Sidebar menu');
        $menu->setChildrenAttribute('class', 'sidebar-menu');

        $menu->addChild('CONTROL PANEL');

        $menu->addChild('Dashboard', [
            'route' => 'admin_workbench_dashboard_index',
        ])->setAttribute('class', 'fa fa-dashboard');

        $menu->addChild('Websites', [
            'route' => 'admin_workbench_websites',
        ])->setAttribute('class', 'fa fa-bars');

        $menu->addChild('Analyzes', [
            'route' => 'admin_workbench_analyzes',
        ])->setAttribute('class', 'fa fa-folder-o');

        $menu->addChild('User list', [
            'route' => 'admin_workbench_users',
        ])->setAttribute('class', 'fa fa-group');

        return $menu;
    }
}
