<?php
namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InfoController extends Controller
{

    public function showAction(){
        return $this->render('SiteBundle::map-info.html.twig');
    }

}