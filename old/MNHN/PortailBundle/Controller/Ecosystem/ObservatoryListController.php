<?php
namespace SiteBundle\Controller\Ecosystem;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BuilderBundle\Controller\Builder\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;

use SiteBundle\Entity\P_Structure;

use SiteBundle\Form\P_StructureFormType;

class ObservatoryListController extends Controller
{

    public function showAction(Request $request)
    {
        $nbbypage = 5;
        $em = $this->getDoctrine()->getManager();

        $postedValues = $request->request->all();

        $servicetest = $this->container->get('mnhn_portail.ecosystem.searchfilters');
        
        $result = $servicetest->getObservatories($request, $nbbypage, $postedValues);

        //USE TEMPLATE:
        return $this->render(':' . $this->container->getParameter('template_repo') . '/views/parts:observatory-list.html.twig', array(
            'themes' => $result['themes'],
            'especes' => $result['especes'],
            'niveaux' => $result['niveaux'],
            'items' => $result['items'],
            'filters' => $postedValues,
            'pages' => $result['pages'],
            'nbbypage' => $nbbypage,
            'isblock' => true,
            // 'page' => $page,
            'request' => $request

            //check values
            // 'query' => $Queryprograms,
            // 'Q_Params' => $Q_Params
        ));
    }
}