<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Entity\PageSection;
use App\Form\PageSectionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PageCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content')
                ->setNumOfRows(15)
                ->setRequired(false)
                ->setEmptyData(''),
            CollectionField::new('sections', 'Page Sections')
                ->setEntryType(PageSectionType::class)
                ->setFormTypeOptions([
                    'entry_options' => [
                        'label' => false,
                        'data_class' => PageSection::class,
                    ],
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
                ->setEntryIsComplex(true)
                ->setRequired(false)
                ->setColumns('col-8')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Pages')
            ->setPageTitle(Crud::PAGE_NEW, 'Add Page')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Page')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Page Details');
    }
}