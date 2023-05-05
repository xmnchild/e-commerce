<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\ProductCart;
use App\Entity\ProductOrder;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductOrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductCartRepository;

class OrderController extends AbstractController
{
    #[Route('/api/order/{cartId}', name: 'add_order', methods: ['POST'])]
public function transformCartToOrder(ProductCartRepository $productCartRepository, ProductCart $productCart, EntityManagerInterface $entityManager, int $cartId, CartRepository $cartRepository): Response
{
    // Create a new order object
    $order = new Order();
    $user = $this->getUser();
    $order->setUser($user);
    
    // Set the created at date
    $order->setCreatedAt(new DateTime());
    $cartProduct = $productCartRepository->findBy(['cart' => $cartId]);
    $cartFind = $cartRepository->findOneBy(['id' => $cartId]);

    // Get the product carts from the passed cart object

    //Iterate over the product carts in the cart
    foreach ($cartProduct as $cart) {
        // Create a new product order object

        $productOrder = new ProductOrder();
        $productOrder->setProduct($cart->getProduct());
        $productOrder->setQuantity($cart->getQuantity());
        $productOrder->setAnorder($order);

        // Add the product order object to the order object
        $order->addProductOrder($productOrder);
        $entityManager->persist($productOrder);

        $entityManager->remove($cart);
    }

    $order->setTotalPrice($order->getOrderTotalPrice());

    $entityManager->remove($cartFind);
    

    //dd($order);

    // Persist the order object and the product order objects to the database
    $entityManager->persist($order);
    $entityManager->flush();


    // Serialize the order object to JSON and return it in a response
    return $this->redirectToRoute('orders');
}

#[Route('/api/orders', name: 'orders', methods: ['GET'])]
public function getOrders(Order $orders, OrderRepository $orderRepository): Response
{
    $user = $this->getUser();

    if (!$user) {
        return new JsonResponse(['message' => 'No user found.'], 404);
    }

    try {
        $orders = $orderRepository->findBy(['user' => $user->getId()]);
    } catch (\Exception $e) {
        return new JsonResponse(['message' => 'Error retrieving orders.'], 500);
    }

    return $this->render('order/orders.html.twig', [
        'orders' => $orders,
    ]);
}

#[Route('/api/orders/{orderId}', name: 'oneorder', methods: ['GET', 'POST'])]
public function getOneOrder(int $orderId, OrderRepository $orderRepository, ProductOrderRepository $productOrderRepository): Response
{
    try {
        $order = $orderRepository->findOneBy(['id' => $orderId]);
        
        if (!$order) {
            return new JsonResponse(['message' => 'Order not found.'], 404);
        }
        
        $products = $productOrderRepository->findBy(['anorder' => $orderId]);
        
    } catch (\Exception $e) {
        return new JsonResponse(['message' => 'Error retrieving order.'], 500);
    }

    return $this->render('order/oneorder.html.twig', [
        'order' => $order,
        'products' => $products,
    ]);
}




}
