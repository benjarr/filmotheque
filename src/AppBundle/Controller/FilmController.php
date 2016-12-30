<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Film;
use AppBundle\Form\FilmType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FilmController extends Controller
{
    public function listerAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $films = $em->getRepository('AppBundle:Film')->findAll() ;

        return $this->render('AppBundle:Film:lister.html.twig', array(
            'films' => $films,
        )) ;
    }

    public function editerAction($id = null, Request $request)
    {
        $message = '' ;
        $em = $this->getDoctrine()->getManager() ;

        if (isset($id)) {
            // Modification d'un film existant : on recherche ses données
            $film = $em->find(Film::class, $id) ;

            if (!$film) {
                $message = 'Aucun film trouvé' ;
            }
        }
        else {
            // Ajout d'un nouvel film
            $film = new Film() ;
        }

        $form = $this->createForm(FilmType::class, $film) ;

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($film) ;
            $em->flush() ;

            if (isset($id)) {
                $message = 'Film modifié avec succès !' ;
            }
            else {
                $message = 'Film ajouter avec succès !' ;
            }
        }

        return $this->render('AppBundle:Film:editer.html.twig', array(
            'form'    => $form->createView(),
            'message' => $message,
        )) ;
    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager() ;
        $film = $em->find('AppBundle:Film', $id) ;

        if (!$film) {
            throw new NotFoundHttpException("Film non trouvé") ;
        }

        $em->remove($film) ;
        $em->flush() ;

        return $this->redirectToRoute('film_lister') ;
    }
}
