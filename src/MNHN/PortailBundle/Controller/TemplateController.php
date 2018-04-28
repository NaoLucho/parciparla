<?php

namespace MNHN\PortailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;
use BuilderBundle\Controller\Builder\Utils;

class TemplateController extends Controller
{
    public function basetemplateAction()
    {
        return $this->render('::template_index.html.twig');
        //return $this->render('BuilderBundle:Default:index.html.twig');
    }

    public function indexAction()
    {
        return $this->render('::index.html.twig');
        //return $this->render('BuilderBundle:Default:index.html.twig');
    }


    //TEST DIRECTLY A NEW TEMPLATE 
    public function templateAction($repo, $slug = 'index') //$repo is repository of Open version : OPEN_241117
    {
        //LOAD MENU PRINCIPAL
        $menuprincipal = $this->get('doctrine.orm.entity_manager')
        ->getRepository('BuilderBundle:Menu')
        ->findOneBy(array('name' => 'Principal'));
        $menulinks = [];
        $i = 0;
        //for ($i = 0; $i < count($menuprincipal->getMenuPages()); $i++)
        foreach($menuprincipal->getMenuPages() as $menuPage)
        {
            $menulinks[$i]["title"] = $menuPage->getPage()->getName();
            $menulinks[$i]["path"] = $menuPage->getPage()->getSlug();
            $i = $i +1;
        }


        //LOAD MENU FOOTER
        $menufooter = $this->get('doctrine.orm.entity_manager')
        ->getRepository('BuilderBundle:Menu')
        ->findOneBy(array('name' => 'Footer'));
        $footerlinks = [];
        $i = 0;
        //for ($i = 0; $i < count($menuprincipal->getMenuPages()); $i++)
        foreach($menufooter->getMenuPages() as $menuPage)
        {
            $footerlinks[$i]["title"] = $menuPage->getPage()->getName();
            $footerlinks[$i]["path"] = $menuPage->getPage()->getSlug();
            $i = $i +1;
        }


        //CHECK IF PAGE EXISTS with $slug.html.twig in template repository
        //if ($this->get('twig')->getLoader()->exists(':'.$repo.'/views:'.$slug.'.html.twig')) {    
            return $this->render(':'.$repo.'/views:'.$slug.'.html.twig', array(
                'repo' => $repo,
                'menulinks' => $menulinks,
                'footerlinks' => $footerlinks,
            ));
        //}
    }

    
    // // CONTROLLER OBSO, C'est le pageBuilder de AdminBundle qui gere le mainbuilder
    // //C EST LE BUILDER PRINCIPAL DES PAGES DU SITE
    // // CHARGE LE LIENS MENU PRINCIPAL (menulinks)
    // // CHARGE LES LIENS FOOTER (footerlinks)
    // // CHARGE LE CONTENU DE LA PAGE:
    // // 1: Charge le template twig s'il existe dans app/"%template_repo%"/views:'.$slug.'.html.twig'
    // // 2: sinon si le slug correspond à une Page, charge la page avec le builder
    // // 3: sinon si la page n'existe pas en base de donnée, alors on veut intégrer dans la page le rendu d'un autre controller:
    // // coté TWIG on appelle le controller BuilderBundle:Builder/BuildContent:buildWithController avec controllerUrl = slug en paramettre
    // // IE 
    // public function buildPageOLDAction( $slug, Request $request) 
    // {
    //     //$template_repo is repository of OPEN version : OPEN_241117
    //     $template_repo = $this->container->getParameter('template_repo');;
        
    //     //LOAD MENU PRINCIPAL
    //     $menuprincipal = $this->get('doctrine.orm.entity_manager')
    //     ->getRepository('BuilderBundle:Menu')
    //     ->findOneBy(array('name' => 'Principal'));
    //     $menulinks = [];$i = 0;

    //     $user = $this->getUser();
    //     foreach($menuprincipal->getMenuPages() as $menuPage)
    //     {
    //         //VERIFIER LES DROITS D'ACCES A LA PAGE:
    //         // On vérifie que l'utilisateur dispose bien du rôle demandé au niveau de la page:
    //         // $hasPageRights = true si le group Users est indiqué
    //         // Sinon on controlle si le user a le group demandé
    //         // ou si l'utilisateur à le role 'ROLE_'+group.name dans toute les responsabilités calculées
    //         $hasPageRights = false;
    //         foreach ($menuPage->getPage()->getRights() as $group) {
    //             if ($group->getName() == "Users") {
    //                 $hasPageRights = true; //All users acce
    //                 break;
    //             } elseif (isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName()))))// $user->hasRole(strtoupper('ROLE_'. $group->getName()))))
    //             {
    //                 $hasPageRights = true; // users has rights
    //                 break;
    //             }
    //         }

    //         if($hasPageRights)
    //         {
    //             $menulinks[$i]["title"] = $menuPage->getPage()->getName();
    //             $menulinks[$i]["path"] = $menuPage->getPage()->getSlug();
    //             $i = $i +1;
    //         }
    //     }
        


    //     //LOAD MENU FOOTER
    //     $menufooter = $this->get('doctrine.orm.entity_manager')
    //     ->getRepository('BuilderBundle:Menu')
    //     ->findOneBy(array('name' => 'Footer'));
    //     $footerlinks = [];
    //     $i = 0;
    //     foreach($menuprincipal->getMenuPages() as $menuPage)
    //     {
    //         //VERIFIER LES DROITS D'ACCES A LA PAGE:
    //         // On vérifie que l'utilisateur dispose bien du rôle demandé au niveau de la page:
    //         // $hasPageRights = true si le group Users est indiqué
    //         // Sinon on controlle si le user a le group demandé
    //         // ou si l'utilisateur à le role 'ROLE_'+group.name dans toute les responsabilités calculées
    //         $hasPageRights = false;
    //         foreach ($menuPage->getPage()->getRights() as $group) {
    //             if ($group->getName() == "Users") {
    //                 $hasPageRights = true; //All users acce
    //                 break;
    //             } elseif (isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName()))))// $user->hasRole(strtoupper('ROLE_'. $group->getName()))))
    //             {
    //                 $hasPageRights = true; // users has rights
    //                 break;
    //             }
    //         }

    //         if($hasPageRights)
    //         {
    //             $menulinks[$i]["title"] = $menuPage->getPage()->getName();
    //             $menulinks[$i]["path"] = $menuPage->getPage()->getSlug();
    //             $i = $i +1;
    //         }
    //     }


    //     //RENDER FOSUSER PAGES:
    //     //{{ render(controller('FOSUserBundle:Security:login')) }}

    //     //CHECK IF PAGE EXISTS IN TEMPLATE REPOSITORY with $slug.html.twig
    //     if ($this->get('twig')->getLoader()->exists(':'.$template_repo.'/views:'.$slug.'.html.twig')) {    
    //         return $this->render(':'.$template_repo.'/views:'.$slug.'.html.twig', array(
    //             'template_repo' => $template_repo,
    //             'menulinks' => $menulinks,
    //             'footerlinks' => $footerlinks,
    //         ));
    //     }
    //     else //CREATE PAGE CONTENT
    //     {
    //         $page = $this->get('doctrine.orm.entity_manager')
    //         ->getRepository('BuilderBundle:Page')
    //         ->findOneBy(array('slug' => $slug));

    //         $user = $this->getUser();
    //         //VERIFIER LES DROITS D'ACCES A LA PAGE:
    //         // On vérifie que l'utilisateur dispose bien du rôle demandé au niveau de la page:
    //             // $hasPageRights = true si le group Users est indiqué
    //             // Sinon on controlle si le user a le group demandé
    //             // ou si l'utilisateur à le role 'ROLE_'+group.name dans toute les responsabilités calculées
    //         $hasPageRights = false;
            
    //         $pageContents = [];
    //         $headerContents = [];
    //         $menuContent = [];
    //         $pContents = [];
    //         $footerContents = [];
    //         $template = 'BuilderBundle:BuildPage:buildpagewithtemplate.html.twig';
    //         $controller = null;
            
    //         if($page != null)
    //         {
    //             foreach ($page->getRights() as $group) {
    //                 if($group->getName() == "Users")
    //                 {
    //                     $hasPageRights = true; //All users can accede
    //                     break;
    //                 } 
    //                 elseif(isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName()))))// $user->hasRole(strtoupper('ROLE_'. $group->getName()))))
    //                 {
    //                     $hasPageRights = true; // users has rights
    //                     break;
    //                 }
    //             }

    //             //VERIFICATION DES DROITS D'ACCES AUX CONTENTS - Coté TEMPLATE:
    //             // Calculé dans le template pour le header/content/footer
    //             // On fait les mêmes vérifications avec is_granted('ROLE_'+group.name)
    //             // Les droits du menu sont gérés dans le template du menu
                
    //             $pageContents = $page->getPageContents();

    //             //CREATION DES CONTENU DE LA PAGE:
    //             //LES MOTS CLEFS SONT DEFINIS DANS LA POSITION DES CONTENT DE LA PAGE:
    //             // "Header"+* s'ajoutera dans le Header de la page
    //             // "MainMenu" => le premier élément trouvé défini le nom du menu principal
    //             // "Content"+* s'ajoutera dans le bloc body de la page
    //             // "Footer"+* s'ajoutera dans le bloc body de la page
            
            

    //             //Filter pageContents: Header, MainMenu, Content, Footer

    //             $headerContents = Utils::filterPageContentPositionStartWith($pageContents, "Header");
    //             //IF headerContents is null: default Header

    //             $menuContent = Utils::filterOnePageContentPositionStartWith($pageContents, "MainMenu");
    //             //IF menuContent is null: default menu (Principal)

    //             //Filter pageContents position startwith
    //             $pContents = Utils::filterPageContentPositionStartWith($pageContents, "Content");

    //             //Filter pageContents position startwith
    //             $footerContents = Utils::filterPageContentPositionStartWith($pageContents, "Footer");

    //             // Possible de changer le template par defaut si un content de la page à la position:
    //             // "Template", son contenu devra être le nom complet du template souhaité
    //             $templateContent = Utils::filterOnePageContentPositionStartWith($pageContents, "Template");
    //             if ($templateContent != null)
    //                 $template = strip_tags($templateContent->getContent()->getContent());

    //             // Evolution possible: pouvoir créer un page_content structure_page indiquant les mots clefs à utiliser main il faut un twig correspondant
    //         }
    //         else
    //         {
    //             //RENDER DIRECTLY CONTROLLER WITH ROUTE IF EXISTS
    //             //CHECK IF EXISTS

    //             //DEFINE CONTROLLER:
    //             $controller = $slug;
    //         }
    //         return $this->render($template, array(
    //             'controller' => $controller,
    //             'page' => $page,
    //             'pageContents' => $pageContents,
    //             'menuContent' => $menuContent,
    //             'headerContents' => $headerContents,
    //             'pContents' => $pContents,
    //             'footerContents' => $footerContents,
    //             'template' => $template,
    //             'user' => $user,
    //             'notallowed' => !$hasPageRights,
    //             'template_repo' => $template_repo,
    //             'menulinks' => $menulinks,
    //             'footerlinks' => $footerlinks,
    //         ));
    //     //return $this->render('BuilderBundle:Default:index.html.twig');
    //     }
    // }
}