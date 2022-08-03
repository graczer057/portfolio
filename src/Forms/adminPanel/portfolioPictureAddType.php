<?php

namespace App\Forms\adminPanel;

use App\Entity\Portfolio;
use App\Repository\PortfolioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class portfolioPictureAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Portfolio::class,
                'query_builder' => function (PortfolioRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.mainTitle', 'ASC');
                },
                'choice_label' => 'mainTitle',
                'label' => 'Tytuł projektu'
            ])
            ->add('pictures', FileType::class, [
                'label' => 'Zdjęcia projektu',
                'required' => true,
                'multiple' => true,
                'attr' => [
                    'multiple' => 'multiple'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj nowe zdjęcia projektowe',
                'attr' => ['class' => 'save']
            ])
        ;
    }
}