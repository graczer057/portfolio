<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class mainEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Imię i nazwisko:',
                'required' => false
            ])
            ->add('title', TextType::class, [
                'label' => 'Tytuł stanowiska',
                'required' => false
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'label' => 'Zdjęcie profilowe',
                'multiple' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edytuj swoje dane na stronie głównej',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}