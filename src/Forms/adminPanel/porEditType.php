<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class porEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainTitle', TextType::class, [
                'label' => 'Opis kafelka:',
                'required' => false
            ])
            ->add('worldLink', TextType::class, [
                'label' => 'Link do strony:',
                'required' => false
            ])
            ->add('githubLink', TextType::class, [
                'label' => 'Link do githuba',
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis projektu:',
                'required' => false
            ])
            ->add('stack', TextType::class, [
                'label' => 'Stack technologiczny projektu:',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edytuj projekt',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}