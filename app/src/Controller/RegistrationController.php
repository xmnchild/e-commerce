<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{
    #[Route('api/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $uuid = Uuid::v4();
            $user->setId($uuid);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            if ($form->get('username')->getData() === 'admin') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            try {
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('login');
            } catch (UniqueConstraintViolationException $e) {
                // check which field caused the violation
                $errorMessage = 'An error occurred';
                if (strpos($e->getMessage(), 'UNIQ_8D93D649E7927C74') !== false) {
                    $form->get('email')->addError(new \Symfony\Component\Form\FormError('Email address already exists', null, ['attr' => ['class' => 'text-blue-500']]));
                    //$form->get('email')->addError(new \Symfony\Component\Form\FormError($errorMessage));
                } elseif (strpos($e->getMessage(), 'UNIQ_8D93D649F85E0677') !== false) {
                    $form->get('username')->addError(new \Symfony\Component\Form\FormError('Username already exists', null, ['attr' => ['class' => 'text-blue-500']]));
                }

                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'errorMessage' => $errorMessage
                ]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }
}





