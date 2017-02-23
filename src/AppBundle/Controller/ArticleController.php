<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;




class ArticleController extends Controller
{
    /**
     * @Route("/articles" , name="articles")
     */
    public function articlesAction()
    {
    	 $em = $this->getDoctrine()->getManager();
        $articles= $em->getRepository("AppBundle:Article")
                        ->findAll();

        return $this->render('admin/articles.html.twig' ,[ 'articles' => $articles ]);
    }

    

	/**
     * @Route("/article" , name="article")
     */
    public function showAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
        $article= $em->getRepository("AppBundle:Article")
            ->find($id);

        if (empty($article)) {
            throw $this->createNotFoundException("Cet article n'existe pas");
        }

       
        return $this->render('Public/show_article.html.twig' , [ 'article' => $article ]);
    }


    /**
     * @Route("/nouvelArticle" , name="nouvelArticle")
     */
    public function createAction(Request $request)
    {
    	$article = new Article();

    	$formArticle = $this->createForm(ArticleType::class , $article);
        $formArticle->handleRequest($request); 

        if ($formArticle->isSubmitted() && $formArticle->isValid())
        {
        	$em = $this->getDoctrine()->getManager();

        	//recuperation de l'image
			$image = $article->getImage();

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
				$image->move('upload/article' , $fileName );
			}

			


			//non unique ds la BDD
			$article->setImage($fileName);


        	$em->persist($article);
            $em->flush();

            $this->addFlash('success' , 'Votre article a bien été ajouté');

            return $this->redirectToRoute('nouvelArticle');
        }

         return $this->render('admin/create_article.html.twig', [
            'formArticle' => $formArticle->createView() , 'article' => $article
        ]);

    }


    /**
     * @Route("/article/modifier/{id}", name="modifArticle" , requirements={"id" = "\d+"})
     */
    public function modifyAction(Request $request , $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $article= $em->getRepository("AppBundle:Article")
            ->find($id);

        if(!$article)
        {
            throw $this->createNotFoundException('Cet article n\'existe pas');
        }

        $formArticle = $this->createForm(ArticleType::class , $article);
        $formArticle->handleRequest($request);

        if ($formArticle->isSubmitted() && $formArticle->isValid())
        {
        	$em = $this->getDoctrine()->getManager();

        	//recuperation de l'image
			$image = $article->getImage();

			//service utils
			$serviceUtils = $this->get('app.service.utils.string');
			$fileName = $serviceUtils->generateUniqId() . '.' .$image->guessExtension() ;

			//transfert de l'image
			$image->move('upload/article' , $fileName );


			//non unique ds la BDD
			$article->setImage($fileName);

        	$em->persist($article);
            $em->flush();

            $this->addFlash('success' , 'Votre article a bien été modifié');

            return $this->redirectToRoute('article', ['id' => $id]);
        }


        return $this->render('admin/create_article.html.twig', [
            'formArticle' => $formArticle->createView() , 'article' => $article
        ]);

    }


    /**
     * @Route("/article/supprimer/{id}", name="supprArticle" , requirements={"id" = "\d+"})
     */
    public function removeAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $article= $em->getRepository("AppBundle:Article")
            ->find($id);

        if(!$article)
        {
            throw $this->createNotFoundException('Cet article n\'existe pas');
        }

        $em->remove($article);
        $em->flush();

        $this->addFlash('success3' , 'Votre article a bien été supprimé');


        // Redirection sur la page qui liste tous les produits
        return $this->redirectToRoute('articles');

    }




}