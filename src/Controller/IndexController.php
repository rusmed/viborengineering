<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
//        return $this->render('index/index.html.twig', [
//            'controller_name' => 'IndexController',
//        ]);

        return new Response(<<<EOF
            <html>
                <body>
                    <img src="/images/ok.png" />
                </body>
            </html>
            EOF
        );
    }
}
