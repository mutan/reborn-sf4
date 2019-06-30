<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Liquid;
use App\Repository\LiquidRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cost',TextType::class)
            ->add('liquids',TextType::class)
            ->add('manager', EntityType::class, [
                'class'       => Liquid::class,
                'query_builder' => function (LiquidRepository $liquidRepository) {
                    return $liquidRepository->findAll();
                },
                'placeholder' => ''
            ])


//            ->add('name',TextType::class)
//            ->add('quantity',TextType::class)
//            ->add('description',TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}