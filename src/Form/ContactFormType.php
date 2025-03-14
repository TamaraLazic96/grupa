<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormType extends AbstractType
{
    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('contact.name'),
            ])
            ->add('subject', TextType::class, [
                'label' => $this->translator->trans('contact.subject'),
            ])
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('contact.email'),
            ])
            ->add('message', TextareaType::class, [
                'label' => $this->translator->trans('contact.message'),
                'attr' => ['rows' => 5],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('contact.send'),
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
