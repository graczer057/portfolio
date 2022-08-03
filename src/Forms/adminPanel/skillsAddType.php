<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class skillsAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Kategoria:',
                'choices' => [
                    'Miękkie' => 1,
                    'Twarde' => 2
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nazwa Umiejętności:'
            ])
            ->add('percentage', IntegerType::class, [
                'label' => 'Wartość procentowa:'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj nową umiejętność',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}