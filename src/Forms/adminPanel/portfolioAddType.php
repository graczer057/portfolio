<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class portfolioAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainTitle', TextType::class, [
                'label' => 'Opis kafelka:'
            ])
            ->add('worldLink', TextType::class, [
                'label' => 'Link do strony:',
                'required' => false
            ])
            ->add('githubLink', TextType::class, [
                'label' => 'Link do githuba'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis projektu:'
            ])
            ->add('stack', TextType::class, [
                'label' => 'Stack technologiczny projektu:'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj nowy projekt',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}