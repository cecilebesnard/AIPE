<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        
    	$em = $this->getDoctrine()->getManager();
        $articles= $em->getRepository("AppBundle:Article")
                        ->findAll();


        // replace this example code with whatever you need
        return $this->render('public/index.html.twig' ,[ 'articles' => $articles ] );
    }


    /**
     * @Route("/mentionsLegales", name="mentions")
     */
    public function mentionsAction(Request $request)
    {
        

        // replace this example code with whatever you need
        return $this->render('public/mentionsLegales.html.twig');
    }


}



