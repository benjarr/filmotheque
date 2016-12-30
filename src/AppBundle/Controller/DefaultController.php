<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render(':default:index.html.twig') ;
    }

    public function topActeursAction($max = 5)
    {
        $em = $this->getDoctrine()->getManager() ;

        $acteurs = $em->getRepository('AppBundle:Acteur')->getJeunesActeurs($max) ;

        return $this->render('AppBundle:Acteur:liste.html.twig', array(
            'acteurs' => $acteurs,
        )) ;
    }

    public function topFilmsAction($max = 5)
    {
        $em = $this->getDoctrine()->getManager() ;

        $films = $em->getRepository('AppBundle:Film')->getTopFilms($max) ;

        return $this->render('AppBundle:Film:liste.html.twig', array(
            'films' => $films,
        )) ;
    }
}
