<?php
namespace MNHN\PortailBundle\Controller\Ecosystem;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BuilderBundle\Controller\Builder\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;

use MNHN\PortailBundle\Entity\P_Structure;

use MNHN\PortailBundle\Form\P_StructureFormType;

class EcosystemController extends Controller
{

    public function showAction($page = 1, Request $request)
    {

        $nbbypage = 10;
        $em = $this->getDoctrine()->getManager();

        $postedValues = $request->request->all();

        $servicetest = $this->container->get('mnhn_portail.ecosystem.searchfilters');

        $query = $request->query->all();
        if(isset($query['page'])) {
            $page = intval($query['page']);
        }

        $result = $servicetest->getObservatories($request, $nbbypage, $postedValues, $page);


        $structures = $em->getRepository('MNHNPortailBundle:P_Structure')->findBy(array('isActive' => true));

        $structureArray = [];

        $structure_logo_route = $this->container->getParameter('structure_logo_route');

        foreach ($structures as $key => $structure) {
            $structureArray[$key]["name"] = $structure->getName();
            $structureArray[$key]["lat"] = $structure->getLatitude();
            $structureArray[$key]["long"] = $structure->getLongitude();
            $structureArray[$key]["id"] = $structure->getId();
            $structureArray[$key]["logo"] = $structure_logo_route . '/' . $structure->getImageName();
        }

        $this->get('mnhn_portail.js_vars')->structures = $structureArray;

        //USE TEMPLATE:
        return $this->render(':'.$this->container->getParameter('template_repo').'/views:ecosysteme.html.twig', array(
            'themes' => $result['themes'],
            'especes' => $result['especes'],
            'niveaux' => $result['niveaux'],
            'items' => $result['items'],
            'filters' => $postedValues,
            'structures' => $structures,
            'pages' => $result['pages'],
            'nbbypage' => $nbbypage,
            'page' => $page,
            'request' => $request

            //check values
            // 'query' => $Queryprograms,
            // 'Q_Params' => $Q_Params
        ));
    }
}