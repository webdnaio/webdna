<?php

namespace WebDNA\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebDNAUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
