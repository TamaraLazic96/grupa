<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostCrudController extends AbstractCrudController {

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            Field::new('thumbnailFile', 'Thumbnail')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            ImageField::new('thumbnail')
                ->setBasePath('/uploads/posts')
                ->onlyOnIndex(),
            TextEditorField::new('content')
                ->setNumOfRows(20),
            AssociationField::new('categories', 'Categories')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->setRequired(true),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Posts')
            ->setPageTitle(Crud::PAGE_NEW, 'Add Post')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Post')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Post Details');
    }

    public function createEntity(string $entityFqcn): Post
    {
        $category = new Post();
        $user = $this->security->getUser();

        if ($user) $category->setAuthor($user);

        return $category;
    }
}