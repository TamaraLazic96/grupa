<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController {

    #[Route('/posts/{page}', name: 'posts', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function posts(PostRepository $postRepository, int $page = 1): Response
    {
        $postsPerPage = 8;
        $posts = $postRepository->findAllPosts($postsPerPage, ($page - 1) * $postsPerPage);

        $totalPosts = $postRepository->count();
        $totalPages = ceil($totalPosts / $postsPerPage);

        return $this->render('Post/posts.html.twig', [
            'posts' => $posts,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
    }

    #[Route('/post/{id}', name: 'show_post', requirements: ['id' => '\d+'])]
    public function showPost(PostRepository $postRepository, int $id): Response
    {
        $post = $postRepository->find($id);

        return $this->render('Post/show-post.html.twig', [
            'post' => $post,
        ]);
    }
}