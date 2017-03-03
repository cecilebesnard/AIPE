<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Agenda;

class AgendaController extends Controller
{
    /**
     * @Route("/Agenda", name="agenda")
     */
    public function agendaAction(Request $request)
    {
        
    	$em = $this->getDoctrine()->getManager();
        $agendas= $em->getRepository("AppBundle:Agenda")
                        ->agenda();

        return $this->render('public/agenda.html.twig' ,[ 'agendas' => $agendas ]);
    }
}
