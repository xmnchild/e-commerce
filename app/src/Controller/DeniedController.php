<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeniedController extends AbstractController
{
    #[Route('/error/denied', name: 'denied')]
    public function index(): Response
    {

        $statusCode = Response::HTTP_UNAUTHORIZED;


        return $this->render('error/denied.html.twig', [
            'controller_name' => 'DeniedController',
            'statusCode' => $statusCode
        ]);
    }
}
