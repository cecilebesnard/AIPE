<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class,[
                        'constraints' =>
                        [
                            new Assert\NotBlank(['message' => '* Veuillez renseigner un nom d\'utilisateur'])
                        ]
                        ])
                ->add('password' , PasswordType::class,[
                        'constraints' =>
                        [
                            new Assert\NotBlank(['message' => '* Veuillez renseigner un mot de passe'])
                        ]
                        ])
                ->add('email', EmailType::class,[
                        'constraints' =>
                        [
                            new Assert\NotBlank(['message' => '* Veuillez renseigner un Email'])
                        ]
                        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
