<?php

namespace MNHN\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MNHNAdminBundle:Default:index.html.twig');
    }
}
