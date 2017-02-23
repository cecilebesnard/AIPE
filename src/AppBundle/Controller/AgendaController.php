<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Form\AgendaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;




class AgendaController extends Controller
{
    /**
     * @Route("/Agenda" , name="Agenda")
     */
    public function AgendaAction()
    {
    	 $em = $this->getDoctrine()->getManager();
        $agendas= $em->getRepository("AppBundle:Agenda")
                        ->findAll();

        return $this->render('admin/agenda.html.twig' ,[ 'agenda' => $Agenda ]);
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
            throw $this->createNotFoundException("Cet evenement n'existe pas");
        }

       
        return $this->render('Public/show_evenement.html.twig' , [ 'evenement' => $evenement ]);
    }


    /**
     * @Route("/nouvelevenement" , name="nouvelevenement")
     */
    public function createAction(Request $request)
    {
    	$evenement = new agenda();

    	$formEvenement = $this->createForm(agendaType::class , $evenement);
        $formEvenement->handleRequest($request); 

        if ($formEvenement->isSubmitted() && $formEvenement->isValid())
        {
        	$em = $this->getDoctrine()->getManager();

        	/*
            //recuperation de l'image
			$image = $evenement->getImage();

			if(empty($image))
			{
				$fileName = "imagenondisponible.png";
			}
			else
			{
				//service utils
				$serviceUtils = $this->get('app.service.utils.string');
				$fileName = $serviceUtils->generateUniqId() . '.' .$image->guessExtension() ;
				//transfert de l'image
				$image->move('upload/agenda' , $fileName );
			}

			//non unique ds la BDD
			$agenda->setImage($fileName);*/


        	$em->persist($evenement);
            $em->flush();

            $this->addFlash('success' , 'Votre evenement a bien été ajouté');

            return $this->redirectToRoute('nouvelevenement');
        }

         return $this->render('admin/create_evenement.html.twig', [
            'formEvenement' => $formEvenement->createView() , 'evenement' => $evenement
        ]);

    }


    /**
     * @Route("/evenement/modifier/{id}", name="modifevenement" , requirements={"id" = "\d+"})
     */
    public function modifyAction(Request $request , $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository("AppBundle:agenda")
            ->find($id);

        if(!$evenement)
        {
            throw $this->createNotFoundException('Cet evenement n\'existe pas');
        }

        $formEvenement = $this->createForm(agendaType::class , $evenement);
        $formEvenement->handleRequest($request);

        if ($formEvenement->isSubmitted() && $formEvenement->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            /*
            //recuperation de l'image
            $image = $evenement->getImage();

            if(empty($image))
            {
                $fileName = "imagenondisponible.png";
            }
            else
            {
                //service utils
                $serviceUtils = $this->get('app.service.utils.string');
                $fileName = $serviceUtils->generateUniqId() . '.' .$image->guessExtension() ;
                //transfert de l'image
                $image->move('upload/agenda' , $fileName );
            }

            //non unique ds la BDD
            $agenda->setImage($fileName);*/


            $em->persist($evenement);
            $em->flush();

            $this->addFlash('success' , 'Votre evenement a bien été modifié');

            return $this->redirectToRoute('agenda', ['id' => $id]);
        }


        return $this->render('admin/create_evenement.html.twig', [
            'formEvenement' => $formEvenement->createView() , 'evenement' => $evenement
        ]);

    }


    /**
     * @Route("/evenement/supprimer/{id}", name="supprevenement" , requirements={"id" = "\d+"})
     */
    public function removeAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository("AppBundle:agenda")
            ->find($id);

        if(!$evenement)
        {
            throw $this->createNotFoundException('Cet evenement n\'existe pas');
        }

        $em->remove($evenement);
        $em->flush();

        $this->addFlash('success3' , 'Votre evenement a bien été supprimé');


        // Redirection sur la page qui liste tous les produits
        return $this->redirectToRoute('agenda');

    }




}