<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Acteur;
use AppBundle\Form\ActeurRechercheType;
use AppBundle\Form\ActeurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActeurController extends Controller
{
    public function listerAction()
    {
        $em      = $this->getDoctrine()->getManager() ;
        $acteurs = $em->getRepository('AppBundle:Acteur')->findAll() ;

        $form = $this->createForm(ActeurRechercheType::class) ;

        return $this->render('AppBundle:Acteur:lister.html.twig', array(
            'acteurs' => $acteurs,
            'form'    => $form->createView(),
        )) ;
    }

    public function rechercherAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $motcle = $request->request->get('motcle') ;
            $em     = $this->getDoctrine()->getManager() ;

            if ($motcle != '') {
                $acteurs = $em->getRepository('AppBundle:Acteur')->rechercheActeur($motcle) ;
            }
            else {
                $acteurs = $em->getRepository('AppBundle:Acteur')->findAll() ;
            }

            return $this->render('AppBundle:Acteur:liste.html.twig', array(
                'acteurs' => $acteurs,
            )) ;
        }

        return $this->listerAction() ;
    }

    public function editerAction($id = null, Request $request)
    {
        $message = '' ;
        $em = $this->getDoctrine()->getManager() ;

        if (isset($id)) {
            // Modification d'un acteur existant : on recherche ses données
            $acteur = $em->find(Acteur::class, $id) ;

            if (!$acteur) {
                $message = 'Aucun acteur trouvé' ;
            }
        }
        else {
            // Ajout d'un nouvel acteur
            $acteur = new Acteur() ;
        }

        $form = $this->createForm(ActeurType::class, $acteur) ;

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($acteur) ;
            $em->flush() ;

            if (isset($id)) {
                $message = 'Acteur modifié avec succès !' ;
            }
            else {
                $message = 'Acteur ajouter avec succès !' ;
            }
        }

        return $this->render('AppBundle:Acteur:editer.html.twig', array(
            'form'    => $form->createView(),
            'message' => $message,
        )) ;
    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager() ;
        $acteur = $em->find('AppBundle:Acteur', $id) ;

        if (!$acteur) {
            throw new NotFoundHttpException("Acteur non trouvé") ;
        }

        $em->remove($acteur) ;
        $em->flush() ;

        return $this->redirectToRoute('acteur_lister') ;
    }
}
