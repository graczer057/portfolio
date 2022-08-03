<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class expEditType extends AbstractType
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
                'required' => false
            ])
            ->add('title', TextType::class, [
                'label' => 'Nazwa Doświadczenia:',
                'required' => false
            ])
            ->add('desc', TextareaType::class, [
                'label' => 'Opis Doświadczenia:',
                'required' => false
            ])
            ->add('date', DateType::class, [
                'label' => 'Data Rozpoczęcia:',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edytuj doświadczenie',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}