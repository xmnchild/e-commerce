<?php

namespace App\Controller;

use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\LoginFormType;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginController extends AbstractController
{
    #[Route('api/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, UrlGeneratorInterface $urlGenerator, JWTTokenManagerInterface $JWTManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();

            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['username' => $username]);

            if (!$user) {
                $form->get('username')->addError(new \Symfony\Component\Form\FormError('Invalid username', null, ['attr' => ['class' => 'text-blue-500']]));
            }

            else if (!$passwordHasher->isPasswordValid($user, $password)) {
                $form->get('password')->addError(new \Symfony\Component\Form\FormError('Invalid password', null, ['attr' => ['class' => 'text-blue-500']]));
            }

            else {

                $client = HttpClient::create();

                 $response = $client->request('POST', 'http://'.$_SERVER['SERVER_ADDR'].'/api/login_check', [
            #    $response = $client->request('POST', 'http://localhost:8000/api/login_check', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);

            $responseContent = $response->getContent();
            $data = json_decode($responseContent, true);
            $token = $data['token'];

            $cookie = new Cookie('JWT', $token, time() + 3600, '/', null, false, true);
            $response = new RedirectResponse($urlGenerator->generate('home'));
            $response->headers->setCookie($cookie);
            return $response;

            }
        }

        return $this->render('login/login.html.twig', [
            'loginForm' => $form->createView(),
            
        ]);
    }
}