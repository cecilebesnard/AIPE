<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Form\AgendaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;




class AgendaAdminController extends Controller
{
    /**
     * @Route("/admin/AgendaAdmin" , name="agendaAdmin")
     */
    public function agendaAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $agendas= $em->getRepository("AppBundle:Agenda")
                        ->agenda();

        /*$agendas= $em->getRepository("AppBundle:Agenda")
                        ->findAll();

        $years = $em->getRepository("AppBundle:Agenda")
                        ->yearAgenda();

        $months = $em->getRepository("AppBundle:Agenda")
                        ->monthAgenda();

        $days = $em->getRepository("AppBundle:Agenda")
                        ->monthAgenda();

        //die(dump($years));*/

                        //die(dump($agendas));

        return $this->render('admin/agenda.html.twig' ,[ 'agendas' => $agendas ]);
    }

    

	/**
     * @Route("/agenda" , name="agenda")
     */
    public function showAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository("AppBundle:Agenda")
            ->find($id);

        if (empty($evenement)) {
            throw $this->createNotFoundException("Cet évènement n'existe pas");
        }

       
        return $this->render('Public/show_evenement.html.twig' , [ 'evenement' => $evenement ]);
    }


    /**
     * @Route("/admin/nouvelEvenement" , name="nouvelEvenement")
     */
    public function createAction(Request $request)
    {
    	$evenement = new agenda();

    	$formEvenement = $this->createForm(agendaType::class , $evenement);
        $formEvenement->handleRequest($request); 

        if ($formEvenement->isSubmitted() && $formEvenement->isValid())
        {
        	$em = $this->getDoctrine()->getManager();

        	$em->persist($evenement);
            $em->flush();

            $this->addFlash('success' , 'Votre évènement a bien été ajouté');

            return $this->redirectToRoute('agendaAdmin');
        }

         return $this->render('admin/create_evenement.html.twig', [
            'formEvenement' => $formEvenement->createView() , 'evenement' => $evenement
        ]);

    }


    /**
     * @Route("/admin/evenement/modifier/{id}", name="modifevenement" , requirements={"id" = "\d+"})
     */
    public function modifyAction(Request $request , $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository("AppBundle:Agenda")
            ->find($id);

        if(!$evenement)
        {
            throw $this->createNotFoundException('Cet évènement n\'existe pas');
        }

        $formEvenement = $this->createForm(agendaType::class , $evenement);
        $formEvenement->handleRequest($request);

        if ($formEvenement->isSubmitted() && $formEvenement->isValid())
        {
            $em = $this->getDoctrine()->getManager();
 
            $em->persist($evenement);
            $em->flush();

            $this->addFlash('success' , 'Votre évènement a bien été modifié');

            return $this->redirectToRoute('agendaAdmin');
        }


        return $this->render('admin/create_evenement.html.twig', [
            'formEvenement' => $formEvenement->createView() , 'evenement' => $evenement
        ]);

    }


    /**
     * @Route("/admin/evenement/supprimer/{id}", name="supprevenement" , requirements={"id" = "\d+"})
     */
    public function removeAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository("AppBundle:Agenda")
            ->find($id);

        if(!$evenement)
        {
            throw $this->createNotFoundException('Cet évènement n\'existe pas');
        }

        $em->remove($evenement);
        $em->flush();

        $this->addFlash('success' , 'Votre évènement a bien été supprimé');


        // Redirection sur la page qui liste tous les produits
        return $this->redirectToRoute('agendaAdmin');

    }




}