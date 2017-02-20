<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
       
    	 $formContact = $this->createFormBuilder()
            ->add('firstname', TextType::class,[
                'constraints' =>
                [
                    new Assert\NotBlank(['message' => 'Veuillez rentrer un prénom']),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit faire 2 caractères minumum'
                    ])
                ]
            ])
            ->add('lastname', TextType::class,[
                'constraints' =>
                    [
                        new Assert\NotBlank(['message' => 'Veuillez rentrer un nom'])
                    ]
                    ])


            ->add('email', EmailType::class, [
                'constraints' =>
                    [

                        new Assert\NotBlank(['message' => 'Veuiller rentrer un email']),
                        new Assert\Email([
                            'message' => 'Votre email "{{ value }}" est faux'
                        ])
                    ]
            ])
            ->add('content', TextareaType::class,[
                'constraints' =>
                [
                    new Assert\NotBlank(['message' => 'Veuillez rentrer un message']),
                    new Assert\Length([
                        'max' => 150,
                        'maxMessage' => '150 caractères maximum'
                    ])
                ]

            ])
           
            ->getForm();

        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            // La technique a utilisée est d'utiliser une varabiel ex: $data et de manipuler cette variable
            $data = $formContact->getData();
            //dump($data);

            // Envoi du mail
            $message = \Swift_Message::newInstance()
                ->setSubject('Formulaire de contact')
                ->setFrom($data['email'])
                ->setTo('mailtest@test.fr')
                ->setBody(
                    $this->renderView('emails/formulaire-contact.html.twig', ["data" => $data]),
                    'text/html'
                )
                ->addPart(
                    $this->renderView('emails/formulaire-contact.txt.twig', ["data" => $data]),
                    'text/plain'
                );
            $this->get('mailer')->send($message);

            // Affichage du message de success
            $this->addFlash('success', 'Votre email a bien été envoyé');
            // Redirection
            return $this->redirectToRoute('contact');

        }



        // replace this example code with whatever you need
        return $this->render('public/contact.html.twig', ["formContact" => $formContact->createView()]);
    }

}