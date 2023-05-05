<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductCart;
use App\Repository\CartRepository;
use App\Repository\ProductCartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddProductCartFormType;



class CartController extends AbstractController
{
    #[Route('api/cart', name: 'cart', methods: ['GET'])]
    public function getCartContent(Cart $cart): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $cart = $user->getCart();
        }
        //dd($cart);

        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
        ]);
    }

    #[Route('api/cart/{productId}', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, Product $product, Cart $cart, EntityManagerInterface $entityManager, int $productId, ProductRepository $productRepository, ProductCartRepository $productCartRepository)
    {
        $product = $productRepository->findProductById($productId);
        $user = $this->getUser();
        
        // Retrieve the user's cart or create a new one
        $cart = $user->getCart();
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
        }
    
         // Check if the product is already in the cart
    $productCart = $productCartRepository->getProductCartByProduct($product);
    if ($productCart) {
        // If it is, increase the quantity
        $productCart->setQuantity($productCart->getQuantity() + 1);
    } else {
        // If not, create a new product cart object
        $productCart = new ProductCart();
        $productCart->setProduct($product);
        $productCart->setCart($cart);
        $productCart->setQuantity(1);
        $cart->addProductCart($productCart);
        //dd($user->getCart());
    }
    
        // Persist the changes to the database
        $entityManager->persist($cart);
        $entityManager->persist($productCart);
        $entityManager->flush();
    
        return $this->redirectToRoute('product');
    }

    #[Route('api/cart/delete/{productId}', name: 'remove_from_cart', methods: ['POST'])]
public function removeFromCart(Request $request, Product $product, Cart $cart, EntityManagerInterface $entityManager, int $productId, ProductRepository $productRepository, ProductCartRepository $productCartRepository)
{
    $product = $productRepository->findProductById($productId);
    $user = $this->getUser();
    
    // Retrieve the user's cart or create a new one
    $cart = $user->getCart();
    if (!$cart) {
        // If there is no cart for the user, redirect to the product page
        return $this->redirectToRoute('product');
    }

    // Check if the product is already in the cart
    $productCart = $productCartRepository->getProductCartByProduct($product);
    if (!$productCart) {
        // If the product is not in the cart, redirect to the product page
        return $this->redirectToRoute('cart');
    }

    // Decrease the quantity or remove the ProductCart object from the cart
    if ($productCart->getQuantity() > 1) {
        $productCart->setQuantity($productCart->getQuantity() - 1);
    } else {
        $cart->removeProductCart($productCart);
        $entityManager->remove($productCart);
    }
    
    // Persist the changes to the database
    $entityManager->persist($cart);
    $entityManager->flush();

    return $this->redirectToRoute('cart');
}



    }

