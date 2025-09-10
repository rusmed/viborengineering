<?php

namespace App\Controller\Admin;

use App\Entity\WorkRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WorkRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WorkRequest::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('user');
        yield TextField::new('objectAddress');

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            yield TextField::new('statusLabel', 'Статус')->onlyOnIndex();
            if ($pageName === Crud::PAGE_DETAIL) {
                // On detail page, show as text too
                yield TextField::new('statusLabel', 'Статус')->onlyOnDetail();
            }
        } elseif ($pageName === Crud::PAGE_EDIT || $pageName === Crud::PAGE_NEW) {
            yield ChoiceField::new('status', 'Статус')
                ->setChoices(array_flip(WorkRequest::AVAILABLE_STATUSES));
        }

        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('updatedAt')->hideOnForm();
    }
}
