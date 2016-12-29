<?php

namespace WebDNA\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    public function editAction(Request $request)
    {
        return parent::editAction($request);
    }
}
