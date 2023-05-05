<?php 

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JWTNotFoundListener
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $url = $this->router->generate('denied');
        $response = new RedirectResponse($url);

        $event->setResponse($response);
    }
}



