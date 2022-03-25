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
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'add_btn_icon' => 'fas fa-plus-circle',
                'add_btn_class' => 'btn add-button mr-1',
                'add_btn_label' => false,
                'delete_btn_class' => 'btn cancel-button mx-1 mb-4',
                'delete_btn_label' => false,
                'delete_btn_icon' => 'fas fa-trash-alt',
                'attr' => [
                    'class' => 'my-2 mx-auto',
                ],
                // On rempli les informations nécessaires au paramétrage de l'EntityType dans "entry_options"
                'entry_options' => [
                    'collapsable' => true,
                    'attr' => [
                        'data-form-collapsable' => false,
                    ],
                    'label' => false,
                    'class' => Country::class,
                    'choice_label' => 'nationality',
                    'choice_value' => 'id',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.nationality', 'ASC')
                        ;
                    }
                ]
            ])
            ->add('genres', CollectionType::class, [
                'entry_type' => EntityType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'add_btn_icon' => 'fas fa-plus-circle',
                'add_btn_class' => 'btn add-button mr-1',
                'add_btn_label' => false,
                'delete_btn_class' => 'btn cancel-button mx-1 mb-4',
                'delete_btn_label' => false,
                'delete_btn_icon' => 'fas fa-trash-alt',
                'attr' => [
                    'class' => 'my-2 mx-auto',
                ],
                'entry_options' => [
                    'collapsable' => true,
                    'attr' => [
                        'data-form-collapsable' => false,
                    ],
                    'label' => false,
                    'class' => Genre::class,
                    'choice_label' => 'name',
                    'choice_value' => 'id',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.name', 'ASC')
                        ;
                    }
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
