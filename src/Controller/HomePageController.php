<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response {

        return $this->render('HomePage/index.html.twig', [
            'message' => 'Prvi kontroler'
        ]);
    }

    #[Route('/about-us', name: 'about-us')]
    public function aboutUs(): Response {

        return $this->render('HomePage/about-us.html.twig', [
            'message' => 'Prvi kontroler'
        ]);
    }
}