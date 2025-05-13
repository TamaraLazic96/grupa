<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', 'Username'),
            TextField::new('firstName', 'First name'),
            TextField::new('lastName', 'Last name'),
            EmailField::new('email', 'Email'),
//            TextField::new('password', 'Password'),
            ChoiceField::new('roles')->setChoices(
                [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Writer' => 'ROLE_WRITER',
                ])
                ->allowMultipleChoices()
                ->renderExpanded()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Users')
            ->setPageTitle(Crud::PAGE_NEW, 'Add User')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit User')
            ->setPageTitle(Crud::PAGE_DETAIL, 'User Details');
    }
}