<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class CategoryCrudController extends AbstractCrudController {

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description')
                ->setNumOfRows(20)
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Categories')
            ->setPageTitle(Crud::PAGE_NEW, 'Add Category')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Category')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Category Details');
    }

    public function createEntity(string $entityFqcn): Category
    {
        $category = new Category();
        $user = $this->security->getUser();

        if ($user) $category->setAuthor($user);

        return $category;
    }
}