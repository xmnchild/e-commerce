<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddProductFormType;
use App\Form\EditProductFormType;



class ProductController extends AbstractController
{
    #[Route('/api/product', name: 'product', methods: ['GET'])]
    public function ProductsList(ProductRepository $productRepository, Cart $cart): Response
    {

        $products = $productRepository->getAllProducts();

        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $cart = $user->getCart();
        }
        

        return $this->render('product/product.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
            'cart' => $cart

        ]);
    }

    #[Route('/api/product/{productId}', name: 'productid', methods: ['GET'])]
    public function ProductInfo(ProductRepository $productRepository, int $productId, Cart $cart): Response
    {

        $product = $productRepository->findById($productId);
        $products = $productRepository->getAllProducts();
        $similarproducts = array_slice($products, 0, 4);

        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $cart = $user->getCart();
        }


        //return new JsonResponse($product);

        return $this->render('product/oneproduct.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'similarproducts' => $similarproducts,
            'cart' => $cart
        ]);
    }

    #[Route('/api/admin/product/add', name: 'add_product', methods: ['POST', 'GET'])]
    public function AddProduct(ProductRepository $productRepository, Request $request, EntityManagerInterface $entityManager, Cart $cart): Response
    {
        $product = new Product();
        $form = $this->createForm(AddProductFormType::class, $product);
        $form->handleRequest($request);
        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $cart = $user->getCart();
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product');

        }

        return $this->render('product/addproduct.html.twig', [
            'AddProductForm' => $form->createView(),
            'cart' => $cart

        ]);
    }

    #[Route('/api/admin/product/delete/{name}', name: 'delete_product', methods: ['POST'])]
public function deleteProduct(ProductRepository $productRepository, EntityManagerInterface $entityManager, string $name): Response
{
    $product = $productRepository->findByName($name);

    if (!$product) {
        return new JsonResponse(['success' => false, 'error' => 'Product not found.']);
    }

    $entityManager->remove($product);
    $entityManager->flush();

    $this->addFlash('success', sprintf('Product "%s" has been deleted.', $product->getName()));

    return $this->redirectToRoute('product');
}

#[Route('/api/admin/product/edit/{id}', name: 'edit_product', methods: ['GET', 'POST', 'PUT'])]
public function editProduct(int $id, Request $request, EntityManagerInterface $entityManager, Product $product, ProductRepository $productRepository): Response
{
    $form = $this->createForm(EditProductFormType::class, $product);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('product/editproduct.html.twig', [
            'EditProductForm' => $form->createView(),
            'product' => $product,


        ]);
}

}