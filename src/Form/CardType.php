<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Card;
use App\Entity\Edition;
use App\Entity\Element;
use App\Entity\Liquid;
use App\Entity\Rarity;
use App\Entity\Subtype;
use App\Entity\Supertype;
use App\Entity\Type;
use App\Repository\ArtistRepository;
use App\Repository\EditionRepository;
use App\Repository\ElementRepository;
use App\Repository\LiquidRepository;
use App\Repository\RarityRepository;
use App\Repository\SubtypeRepository;
use App\Repository\SupertypeRepository;
use App\Repository\TypeRepository;
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
            ->add('name',TextType::class)
            ->add('image',TextType::class)
            ->add('lives',TextType::class)
            ->add('flying',TextType::class)
            ->add('movement',TextType::class)
            ->add('powerWeak',TextType::class)
            ->add('powerMedium',TextType::class)
            ->add('powerStrong',TextType::class)
            ->add('flavor',TextType::class)
            ->add('number',TextType::class)
            ->add('text',TextareaType::class)
            ->add('erratas',TextareaType::class)
            ->add('comments',TextareaType::class)
            ->add('edition', EntityType::class, [
                'class' => Edition::class,
                'query_builder' => function (EditionRepository $editionRepository) {
                    return $editionRepository->createQueryBuilder('e');
                }
            ])
            ->add('rarity', EntityType::class, [
                'class' => Rarity::class,
                'query_builder' => function (RarityRepository $raritiesRepository) {
                    return $raritiesRepository->createQueryBuilder('r');
                }
            ])
            ->add('artists', EntityType::class, [
                'class' => Artist::class,
                'query_builder' => function (ArtistRepository $artistRepository) {
                    return $artistRepository->createQueryBuilder('a');
                }
            ])
            ->add('elements', EntityType::class, [
                'class' => Element::class,
                'query_builder' => function (ElementRepository $elementRepository) {
                    return $elementRepository->createQueryBuilder('e');
                }
            ])
            ->add('liquids', EntityType::class, [
                'class' => Liquid::class,
                'query_builder' => function (LiquidRepository $liquidRepository) {
                    return $liquidRepository->createQueryBuilder('l');
                }
            ])
            ->add('Subtypes', EntityType::class, [
                'class' => Subtype::class,
                'query_builder' => function (SubtypeRepository $subtypeRepository) {
                    return $subtypeRepository->createQueryBuilder('s');
                }
            ])
            ->add('Supertypes', EntityType::class, [
                'class' => Supertype::class,
                'query_builder' => function (SupertypeRepository $supertypeRepository) {
                    return $supertypeRepository->createQueryBuilder('s');
                }
            ])
            ->add('Types', EntityType::class, [
                'class' => Type::class,
                'query_builder' => function (TypeRepository $typeRepository) {
                    return $typeRepository->createQueryBuilder('t');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
