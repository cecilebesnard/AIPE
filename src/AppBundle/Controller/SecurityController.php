<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/acces-client", name="security.acces.client")
     */
    public function accesClientAction()
    {
        if($this->isGranted('ROLE_ADMIN'))
        {
            return new Response('<h1>Accés Client</h1>');
        }else{
            return new Response('<h1>pas d\'accés Client</h1>');
        }
    }


    /**
     * @Route("/login", name="security.login")
     */
    public function loginAction()
    {

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('Security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));

    }



    /**
     * @Route("/logout", name="security.logout")
     */
    public function logoutAction()
    {
        //fonction vide
    }

    /**
     * @Route("/admin/account", name="security.account")
     */
    public function accountAction(Request $request)
    {
        $user = new User();

        $formUser = $this->createForm(UserType::class , $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid())
        {


            //pour sauvegarder en bdd
            $em = $this->getDoctrine()->getManager();

            $data = $formUser->getData();

            //hachage du MDP
            $encoderPassword = $this->get('security.password_encoder');
            $password = $encoderPassword->encodePassword($data, $data->getPassword());
            $data->setPassword($password);

            //renseigner un role par defaut
            $rcRole = $em->getRepository('AppBundle:Role');
            $role = $rcRole->findOneBy(
                [
                    'name'=>'ROLE_ADMIN'
                ]
            );
            $serviceToken = $this->get('app.service.utils.string');
            $token = $serviceToken->generateUniqId();

            $data->addRole($role)
                 ->setIsActive(0)
                 ->setToken($token);
            $em->persist($data);
            $em->flush();

            // Envoi du mail
            $message = \Swift_Message::newInstance()
                ->setSubject('Formulaire de contact')
                ->setFrom('aipecloyes@outlook.fr')
                ->setTo('aipecloyes@outlook.fr')
                ->setBody(
                    $this->renderView('emails/account.html.twig', ["data" => $data]),
                    'text/html'
                )
                ->addPart(
                    $this->renderView('emails/account.txt.twig', ["data" => $data]),
                    'text/plain'
                );
            $this->get('mailer')->send($message);

            $this->addFlash('success' , 'Le compte a bien été ajouté');

            return $this->redirectToRoute('security.account');
        }

        
        $em = $this->getDoctrine()->getManager();
        $users= $em->getRepository("AppBundle:User")
                        ->findAll();
        
        return $this->render('security/accountCreate.html.twig', [
            'formUser' => $formUser->createView() , 'user' => $user , 'users' => $users
        ]);
    }

    /**
     * @Route("/confirmation/{token}/{id}", name="security.confirmation")
     */
    public function confirmationAction($token, $id)
    {


        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository("AppBundle:User")
            ->find($id);

        $tokenUser = $user->getToken();

        if($token == $tokenUser && $user->getIsActive() == 0 )
        {
            $user->setIsActive(1);
            $em->persist($user);
            $em->flush();
            return $this->render('security/accountConfirmation.html.twig' , ['user' => $user]);
        }

        return $this->render('security/accountNoConfirmation.html.twig');



    }
    
    
    /**
     * @Route("/admin/account/supprimer/{id}", name="suppraccount" , requirements={"id" = "\d+"})
     */
    public function removeAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $account= $em->getRepository("AppBundle:User")
            ->find($id);

        if(!$account)
        {
            throw $this->createNotFoundException('Ce compte n\'existe pas');
        }

        $em->remove($account);
        $em->flush();

        $this->addFlash('success' , 'Le compte a bien été supprimé');


        // Redirection sur la page qui liste tous les produits
        return $this->redirectToRoute('security.account');

    }
}