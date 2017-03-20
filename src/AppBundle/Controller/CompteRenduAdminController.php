<?php

namespace AppBundle\Controller;

use AppBundle\Entity\compteRendu;
use AppBundle\Form\compteRenduType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;





class CompteRenduAdminController extends Controller
{
    /**
     * @Route("/admin/comptesRendus" , name="comptesRendus")
     */
    public function comptesRendusAction(Request $request)
    {
    	 $em = $this->getDoctrine()->getManager();
        $comptesRendus= $em->getRepository("AppBundle:compteRendu")
                        ->findAll();
        
        
        
        $compteRendu = new compteRendu();

    	$formcompteRendu = $this->createForm(compteRenduType::class , $compteRendu);
        $formcompteRendu->handleRequest($request); 

        if ($formcompteRendu->isSubmitted() && $formcompteRendu->isValid())
        {
        	$em = $this->getDoctrine()->getManager();

        	//recuperation du fichier
			$fichier = $compteRendu->getFichier();

			if(empty($fichier))
			{
				$fileName = "pas de fichier enregistré";
			}
			else
			{
				//service utils
				$serviceUtils = $this->get('app.service.utils.string');
				$fileName = $serviceUtils->generateUniqId() . '.' .$fichier->guessExtension() ;
				//transfert de l'image
				$fichier->move('upload/compte_rendu' , $fileName );
			}

			


			//non unique ds la BDD
			$compteRendu->setFichier($fileName);


        	$em->persist($compteRendu);
            $em->flush();

            $this->addFlash('success' , 'Votre compte-rendu a bien été ajouté');

            return $this->redirectToRoute('comptesRendus');

        }
        return $this->render('admin/compte_rendu.html.twig' ,[ 'comptesRendus' => $comptesRendus , 'formcompteRendu' => $formcompteRendu->createView() , 'compteRendu' => $compteRendu ]);
    }

    
    
    /**
     * @Route("/admin/compteRendu/modifier/{id}", name="modifcompteRendu" , requirements={"id" = "\d+"})
     */
    public function modifyAction(Request $request , $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $comptesRendus= $em->getRepository("AppBundle:compteRendu")
                        ->findAll();
        
        $em = $this->getDoctrine()->getManager();
        $compteRendu= $em->getRepository("AppBundle:compteRendu")
            ->find($id);
        
        $fichierold = $compteRendu->getFichier();
        //die(dump($fichier));
        
        if(!$compteRendu)
        {
            throw $this->createNotFoundException('Ce compte-rendu n\'existe pas');
        }

        $formcompteRendu = $this->createForm(compteRenduType::class , $compteRendu);
        $formcompteRendu->handleRequest($request);

        if ($formcompteRendu->isSubmitted() && $formcompteRendu->isValid())
        {
        	$em = $this->getDoctrine()->getManager();

        	//recuperation de l'image
			$fichier = $compteRendu->getFichier();
                        
                        if(empty($fichier) )
                        {
                            $fileName = $fichierold;
                        }
                        else
                        {
                        //die(dump($fichier));
			//service utils
			$serviceUtils = $this->get('app.service.utils.string');
			$fileName = $serviceUtils->generateUniqId() . '.' .$fichier->guessExtension() ;
                        
			//transfert de l'image
			$fichier->move('upload/compte_rendu' , $fileName );

                        }
			//non unique ds la BDD
			$compteRendu->setFichier($fileName);
                        
        	$em->persist($compteRendu);
            $em->flush();

            $this->addFlash('success' , 'Votre compte-rendu a bien été modifié');

            return $this->redirectToRoute('comptesRendus');
        }


        return $this->render('admin/compte_rendu.html.twig', [
            'formcompteRendu' => $formcompteRendu->createView() , 'compteRendu' => $compteRendu , 'comptesRendus' => $comptesRendus
        ]);

    }


    /**
     * @Route("/admin/compteRendu/supprimer/{id}", name="supprcompteRendu" , requirements={"id" = "\d+"})
     */
    public function removeAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $compteRendu= $em->getRepository("AppBundle:compteRendu")
            ->find($id);

        if(!$compteRendu)
        {
            throw $this->createNotFoundException('Ce compte-rendu n\'existe pas');
        }

        $em->remove($compteRendu);
        $em->flush();

        $this->addFlash('success3' , 'Le compte-rendu a bien été supprimé');


        // Redirection sur la page qui liste tous les produits
        return $this->redirectToRoute('comptesRendus');

    }


    /**
     * @Route("/comptesRendus", name="telechargerCompteRendu")
     */
    public function showAction()
    {
    	 $em = $this->getDoctrine()->getManager();
        $comptesRendus= $em->getRepository("AppBundle:compteRendu")
                        ->findAll();

        return $this->render('public/compte_rendu.html.twig' ,[ 'comptesRendus' => $comptesRendus ]);

    }


}