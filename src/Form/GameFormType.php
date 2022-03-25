<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Publisher;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'game.form.name',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'game.form.description',
            ])
            ->add('publishedAt',DateType::class, [
                'label' => 'game.form.published_at',
                'widget' => 'single_text',
                'attr' => [
                    'min' => date('1980-01-01'),
                    'max' => date('Y-m-d')
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'game.form.price',
                'html5' => true,
            ])
            ->add('thumbnailCover', TextType::class, [
                'label' => 'game.form.thumbnailCover',
            ])
            ->add('thumbnailLogo', TextType::class, [
                'label' => 'game.form.thumbnailLogo',
            ])
            ->add('publisher', EntityType::class, [
                'label' => 'game.form.publisher',
                'class' => Publisher::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.name', 'ASC')
                    ;
                }
            ])
            // Lors de relation ManyToMany il faut utiliser un CollectionType
            ->add('countries', CollectionType::class, [
                'label' => 'game.form.countries',
                // Ici on voulait ajouter plusieurs entity, donc avoir une Collection d'EntityType
                'entry_type' => EntityType::class,
                // Il faut bien penser à ajouter les allow_add et allow_delete afin de pouvoir ajouter les formulaires au fur et à mesure
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    // Pensez à bien garder ce nom de data-selector si vous utilisez mon JS
                    // La valeur du data selector doit être identique à celle du bouton d'ajout
                    'data-list-selector' => 'countries'
                ],
                // On rempli les informations nécessaires au paramétrage de l'EntityType dans "entry_options"
                'entry_options' => [
                    'label' => false,
                    'class' => Country::class,
                    'choice_label' => 'nationality',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nationality', 'ASC')
                        ;
                    }
                ]
            ])
            // Le code du bouton permettant d'ajouter un formulaire de "country" (ici on utilise un EntityType)
            ->add('addCountry', ButtonType::class, [
                'label' => 'game.form.add_country',
                'attr' => [
                    'class' => 'btn btn-info',
                    // Même punition que pour le "data-list-selector" définit dans le CollectionType
                    // On ne change pas son nom (si vous gardez mon JS) et sa valeur doit être identique à celle la CollectionType
                    'data-btn-selector' => 'countries',
                ]
            ])
            ->add('genres', CollectionType::class, [
                'label' => 'game.form.genres',
                'entry_type' => EntityType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-list-selector' => 'genres'
                ],
                'entry_options' => [
                    'label' => false,
                    'class' => Genre::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.name', 'ASC')
                        ;
                    }
                ]
            ])
            ->add('addgenres', ButtonType::class, [
                'label' => 'game.form.add_genre',
                'attr' => [
                    'class' => 'btn btn-info',
                    'data-btn-selector' => 'genres',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.button.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
