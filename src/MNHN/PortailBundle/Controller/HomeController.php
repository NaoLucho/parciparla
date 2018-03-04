<?php

namespace MNHN\PortailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render(':' . $this->container->getParameter('template_repo') . '/views:index.html.twig', array());
    }

    public function countersAction() {

        $em = $this->getDoctrine()->getManager();

        $programs = $em->getRepository('MNHNPortailBundle:P_Program')->createQueryBuilder('programs')
            ->select('COUNT(programs)')
            ->where('programs.isActiveProgram = true') 
            ->getQuery()
            ->getSingleScalarResult();

        $currentyear = intval(date("Y"));
        $lastyear = $currentyear - 1;



        
        $result = $em
        ->getRepository('MNHNPortailBundle:P_Program_ValueByYear')
        ->createQueryBuilder('values')
        ->select('values.nb, values.year, program.id')
        ->innerJoin('values.program_nbSubscriber', 'program')
        ->where('values.year = ' . $currentyear . ' OR values.year = ' . $lastyear)
        ->andWhere('values.nb != 0 ')
        ->andWhere('program.isActiveProgram = true')
        ->addOrderBy('program.id', 'ASC')
        ->addOrderBy('values.year', 'DESC')
        ->getQuery()
        ->getResult();
        
        $participants = 0;
       
        
        //dump($result);
        
        $previousprogramid = 0;

        foreach($result as $value) {
            if( $value['id'] !== $previousprogramid ) {
                $participants += $value['nb'];
            }

            $previousprogramid = $value['id'];
        }

        return $this->render(':' . $this->container->getParameter('template_repo') . '/views/parts:counters.html.twig', array(
            'participants' => $participants,
            'programs' => $programs
        ));
    }

    public function headerAction() {
        return $this->render(':' . $this->container->getParameter('template_repo') . '/views/parts:header.html.twig', array());
    }
}