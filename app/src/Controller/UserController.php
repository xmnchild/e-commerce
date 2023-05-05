<?php

namespace App\Controller;

use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('api/user', name: 'user')]
    public function UserInfo(UserInterface $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, Request $request, Cart $cart): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $cart = $user->getCart();
        }
        $form = $this->createForm(UserFormType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('user'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {

            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Profile updated successfully.');
    
            return $this->redirectToRoute('login');
            }

            elseif ($form->get('change_password')->isClicked()) {
                $oldPassword = $form->get('oldPassword')->getData();
                $newPassword = $form->get('newPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();
            
                if ($userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                    if ($newPassword === $confirmPassword) { 
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $newPassword
                            )
                        );
                        $entityManager->persist($user);
                        $entityManager->flush();
            
                        $this->addFlash('success', 'Password updated successfully.');
            
                        return $this->redirectToRoute('login');

                    } else {
                        $form->addError(new FormError ('The new password and confirm password fields must match.'));
                    }
                } else {
                    $form->addError(new FormError('The old password is incorrect.'));
                }
            }

    }

        return $this->render('user/user.html.twig', [
            'userForm' => $form->createView(),
            'user' => $user,
            'cart' => $cart
        ]);
    }
}
