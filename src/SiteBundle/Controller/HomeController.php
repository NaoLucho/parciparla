<?php
namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{

    public function presentationAction(){
        return $this->render('SiteBundle::presentation.html.twig');
    }

}