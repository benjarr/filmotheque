<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategorieController extends Controller
{
    public function listerAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $categories = $em->getRepository('AppBundle:Categorie')->findAll() ;

        return $this->render('AppBundle:Categorie:lister.html.twig', array(
            'categories' => $categories,
        )) ;
    }

    public function editerAction($id = null, Request $request)
    {
        $message = '' ;
        $em = $this->getDoctrine()->getManager() ;

        if (isset($id)) {
            // Modification d'une categorie existante : on recherche ses données
            $categorie = $em->find(Categorie::class, $id) ;

            if (!$categorie) {
                $message = 'Aucune categorie trouvée' ;
            }
        }
        else {
            // Ajout d'une nouvelle categorie
            $categorie = new Categorie() ;
        }

        $form = $this->createForm(CategorieType::class, $categorie) ;

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($categorie) ;
            $em->flush() ;

            if (isset($id)) {
                $message = 'Categorie modifiée avec succès !' ;
            }
            else {
                $message = 'Categorie ajoutée avec succès !' ;
            }
        }

        return $this->render('AppBundle:Categorie:editer.html.twig', array(
            'form'    => $form->createView(),
            'message' => $message,
        )) ;
    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager() ;
        $categorie = $em->find('AppBundle:Categorie', $id) ;

        if (!$categorie) {
            throw new NotFoundHttpException("Categorie non trouvée") ;
        }

        $em->remove($categorie) ;
        $em->flush() ;

        return $this->redirectToRoute('categorie_lister') ;
    }
}
