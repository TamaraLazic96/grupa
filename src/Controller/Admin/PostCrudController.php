<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PostCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Posts')
            ->setPageTitle(Crud::PAGE_NEW, 'Add Post')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Post')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Post Details');
    }
}