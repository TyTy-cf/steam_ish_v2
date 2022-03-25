<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $class = '';
        if ($options['hidden_submit'] === true) {
            $class = 'd-none';
        }
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'country.index.table.name',
                ]
            ])
            ->add('nationality', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'country.index.table.nationality',
                ]
            ])
            ->add('code', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'country.index.table.code',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.button.submit',
                'attr' => [
                    'class' => $class,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
            'hidden_submit' => false,
        ]);
    }
}
