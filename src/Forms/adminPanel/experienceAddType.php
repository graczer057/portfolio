<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class experienceAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Kategoria:',
                'choices' => [
                    'Doświadczenie' => 1,
                    'Wykształcenie' => 2,
                    'Certyfikaty' => 3,
                    'Projekty' => 4
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Nazwa Doświadczenia:'
            ])
            ->add('desc', TextareaType::class, [
                'label' => 'Opis Doświadczenia:'
            ])
            ->add('date', DateType::class, [
                'label' => 'Data Rozpoczęcia:'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj nowe doświadczenie',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}