<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Categories')
            ->setPageTitle(Crud::PAGE_NEW, 'Add Category')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Category')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Category Details');
    }
}