<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AssociationController extends Controller
{
    /**
     * @Route("/definition_assoc", name="definition_assoc")
     */
    public function definitionassocAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/definition_association.html.twig');
    }

     /**
     * @Route("/statuts", name="statuts")
     */
    public function statutsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/statuts.html.twig');
    }

     /**
     * @Route("/adhesion", name="adhesion")
     */
    public function adhesionAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/adhesion.html.twig');
    }

     /**
     * @Route("/bureau", name="bureau")
     */
    public function bureauAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/bureau.html.twig');
    }


}