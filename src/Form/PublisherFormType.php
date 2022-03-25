<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Publisher;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'publisher.index.table.name'
            ])
            ->add('directorName', TextType::class, [
                'label' => 'publisher.index.table.directorName'
            ])
            ->add('website', TextType::class, [
                'label' => 'publisher.index.table.website'
            ])
            ->add('createdAt',DateType::class, [
                'label' => 'publisher.index.table.created_at',
                'widget' => 'single_text',
                'attr' => [
                    'min' => date('1980-01-01'),
                    'max' => date('Y-m-d')
                ]
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'publisher.index.table.nationality',
                'choice_label' => 'nationality',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nationality', 'ASC')
                    ;
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.button.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publisher::class,
        ]);
    }
}
