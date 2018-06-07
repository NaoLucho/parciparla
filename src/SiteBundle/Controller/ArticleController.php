<?php
namespace SiteBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MNHN\AdminBundle\Controller\Builder\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;

class ArticleController extends Controller
{

    public function showAction(Request $request, $id)
    {
        //dump($request);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        //$date = date("Y-m-d");
        $article = $em->getRepository('SiteBundle:Article')->findOneBy(['id' => $id]);
        $article_group = null;
        if(isset($article)){
            $article_group = $article->getRights();
        }
        $hasRights = false;
        if ($article_group == null || $article_group->getName() == "All"
            || isset($user)
            && ($user->hasGroup($article_group)
            || $this->get('security.authorization_checker')
            ->isGranted(strtoupper('ROLE_' . $article_group->getName())))) {
            $hasRights = true;
        }

        return $this->render('SiteBundle::article.html.twig', array(
            'article' => $article,
            'hasRights' => $hasRights
        ));
    }

    public function listAction(Request $request, $pageContent)
    {
        $numpage = 1;
        $nbbypage = 10;
        $em = $this->getDoctrine()->getManager();
        $postedValues = $request->request->all();
        $query = $request->query->all();
        if (isset($query['p'])) {
            $numpage = intval($query['p']);
        }

        $filter_propertyEntity = null;
        $filter_property = "";
        $valueToFilter = "";

        if (strpos($pageContent->getPosition(), '#') !== false) { //filter or specific param exists
            // position #filterProperty.prop=valueToFilter
            $pageinfos = explode('#', $pageContent->getPosition());
            if (count($pageinfos) > 1) {

                foreach(explode(';', $pageinfos[1]) as $param)
                {
                    if(strpos($param, 'nbbypage')!== false)
                    {
                        $paraminfos = explode('=', $param);
                        if (count($paraminfos) > 1) {
                            $nbbypage = $paraminfos[1];
                        }
                    }
                    else{
                        //filter exists
                        $filterinfos = explode('=', $param);
                        $filter_property = $filterinfos[0];
                        $filter_propertyinfos = explode('.', $filter_property);
                        if(count($filter_propertyinfos)>1){
                            // $filter_property = propertyEntity.property
                            $filter_propertyEntity = $filter_propertyinfos[0];
                            $filter_property = $filter_propertyinfos[1];
                        }
                        $valueToFilter = $filterinfos[1];
                    }
                }
                
            }
        }
        
        
        
        $qb = $em->getRepository('SiteBundle:Article')->createQueryBuilder('entity');
        if (isset($filter_propertyEntity)) {
            //dump($filter_propertyEntity);
            $qb = $qb->leftJoin('entity.' . $filter_propertyEntity, 'entityJoined');
            $qb = $qb->where('entityJoined.' . $filter_property . ' = :filtervalue');
            //dump($filter_property);
        } elseif($filter_property !== "") {
            $qb = $qb->where('entity.' . $filter_property . ' = :filtervalue');
        }
        if($filter_property !== "" && $valueToFilter !== "")
        {
            $qb = $qb->setParameter('filtervalue', $valueToFilter);
            //dump($valueToFilter);
        }
        // $qb = $qb->where('entity.isActive = true');

        $paginator  = new \Doctrine\ORM\Tools\Pagination\Paginator($qb);

        $totalItems = count($paginator);
        $qb->setFirstResult($nbbypage * ($numpage-1)) // set the offset
        ->setMaxResults($nbbypage);
        $items = $qb->getQuery()->getResult();

        
        
        $pages = ceil($totalItems / $nbbypage);
        
        return $this->render('SiteBundle::list-article.html.twig', array(
            'nb_total_items' => $totalItems,
            'items' => $items,
            'pages' => $pages,
            'nbbypage' => $nbbypage,
            'numpage' => $numpage,
            'request' => $request,
            'categorie' => $valueToFilter
        ));
    }
}