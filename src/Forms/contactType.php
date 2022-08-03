<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class contactType extends AbstractType
{
    /* Function responsible for creating contact form */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                    'label' => 'Imię i nazwisko:',
                ])
            ->add('email', TextType::class, [
                'label' => 'Adres email:'
            ])
            ->add('subject', TextType::class, [
                'label' => 'Temat wiadomości:'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis:'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Wyślij wiadomość:',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}