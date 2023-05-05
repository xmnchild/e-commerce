<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;



class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class)
        ->add('lastname', TextType::class)
        ->add('email', EmailType::class)
        ->add('username', TextType::class)
        ->add('oldPassword', PasswordType::class, [
            'label' => 'Old Password',
            'mapped' => false,
            'required' => false
        ])
        ->add('newPassword', PasswordType::class, [
            'label' => 'New Password',
            'mapped' => false,
            'required' => false,

        ])
        ->add('confirmPassword', PasswordType::class, [
            'label' => 'Confirm New Password',
            'mapped' => false,
            'required' => false

        ])
        ->add('save', SubmitType::class)
        ->add('change_password', SubmitType::class)
    ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}