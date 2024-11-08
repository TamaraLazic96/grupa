<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'HomePage')]
    public function index(): Response {
        return $this->render('HomePage/index.html.twig', [
            'message' => 'Prvi kontroler'
        ]);
    }
}