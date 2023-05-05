<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends AbstractController
{
    
    #[Route('/', name: 'redirectionHome')]
    public function redirectionHome()
    {
        return $this->redirectToRoute('home');
    }
    
    #[Route('/home', name: 'home')]
    public function index(ProductRepository $productRepository, Cart $cart): Response
    {
        $products = $productRepository->getAllProducts();
        $featuredProducts = array_slice($products, 0, 3);
        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $cart = $user->getCart();
        }

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'featuredProducts' => $featuredProducts,
            'cart' => $cart
        ]);
    }
}
