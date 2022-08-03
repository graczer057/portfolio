<?php

namespace App\Forms\adminPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class skillsEditType extends AbstractType
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
                'required' => false
            ])
            ->add('name', TextType::class, [
                'label' => 'Nazwa: ',
                'required' => false
            ])
            ->add('percentage', IntegerType::class, [
                'label' => 'Wartość procentowa',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edytuj umiejętność',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}