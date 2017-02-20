<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RepresentantsController extends Controller
{
    /**
     * @Route("/definition", name="definition")
     */
    public function definitionAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/definition_representants.html.twig');
    }


    /**
     * @Route("/conseil", name="conseil")
     */
    public function conseilAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/conseil_ecole.html.twig');
    }

    /**
     * @Route("/representants", name="representants")
     */
    public function representantsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/representants.html.twig');
    }

    /**
     * @Route("/compte_rendu", name="compte_rendu")
     */
    public function compterenduAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('public/compte_rendu.html.twig');
    }
}
