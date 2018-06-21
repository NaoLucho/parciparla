<?php
namespace SiteBundle\Controller;

use SiteBundle\Entity\Comment;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use MNHN\AdminBundle\Controller\Builder\Utils; //FORM BUILDER

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        if (isset($article)) {
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

        //Load comments:
        $comments = $em->getRepository('SiteBundle:Comment')
            ->findBy([
                'article' => $article,
                'isActive' => true
            ]);
        // $comments = $qbComment->getQuery()->getResult();

        //Form comments:
        $formbuilder = $this->createFormBuilder(new Comment());

        $optionAuthor = ['label' => "Auteur"];
        if (isset($user)) {
            $optionAuthor = array(
                'data' => $user->getUsername(),
                'attr' => array(
                    'readonly' => true,
                    'class' => 'align-middle'
                ),
                'label' => "Auteur"
            );
        }

        $formbuilder->add('title', TextType::class, ['label' => 'Titre'])
            ->add('content', TextareaType::class, ['label' => 'Commentaire'])
            ->add('authorName', TextType::class, $optionAuthor)
            ->add('save', SubmitType::class, array('label' => 'Commenter'));

        $form = $formbuilder->getForm();


        $form->handleRequest($request);
        //dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setArticle($article);
            $em->persist($comment);
            $em->flush();

            // Adding a success type message
            $this->addFlash("success", "Votre commentaire est enregistré, il est en attente de validation par le modérateur.");
            //dump($comment);
        }

        return $this->render('SiteBundle::article.html.twig', array(
            'article' => $article,
            'hasRights' => $hasRights,
            'formComment' => $form->createView(),
            'comments' => $comments
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

                foreach (explode(';', $pageinfos[1]) as $param) {
                    if (strpos($param, 'nbbypage') !== false) {
                        $paraminfos = explode('=', $param);
                        if (count($paraminfos) > 1) {
                            $nbbypage = $paraminfos[1];
                        }
                    } else {
                        //filter exists
                        $filterinfos = explode('=', $param);
                        $filter_property = $filterinfos[0];
                        $filter_propertyinfos = explode('.', $filter_property);
                        if (count($filter_propertyinfos) > 1) {
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
        } elseif ($filter_property !== "") {
            $qb = $qb->where('entity.' . $filter_property . ' = :filtervalue');
        }
        if ($filter_property !== "" && $valueToFilter !== "") {
            $qb = $qb->setParameter('filtervalue', $valueToFilter);
            //dump($valueToFilter);
        }
        // $qb = $qb->where('entity.isActive = true');

        $paginArticle = new \Doctrine\ORM\Tools\Pagination\Paginator($qb);
        $totalItems = count($paginArticle);

        $qb->setFirstResult($nbbypage * ($numpage - 1)) // set the offset
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
            'categorie' => $valueToFilter,
        ));
    }
}