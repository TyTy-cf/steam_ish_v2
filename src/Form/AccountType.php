<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'account.index.table.name',
                'attr' => [
                    //'class' => 'bg-dark',
                    'placeholder' => 'account.index.table.name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'account.index.table.email',
                'attr' => [
                    'placeholder' => 'account.index.table.email'
                ]
            ])
            ->add('nickname', TextType::class, [
                'label' => 'account.index.table.nickname',
                // 'mapped' => false, => indique au formulaire qu'il s'agit d'un champ non-mappé dans notre entité
                'required' => false,
                'attr' => [
                    'placeholder' => 'account.index.table.nickname'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'general.button.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /**
         * Le data_class permet de lier l'entité en valeur au formulaire
         * Permettant ainsi d'accéder à ses attributs au sein du formulaire
         * Cela permet donc de relier un formulaire à une entité et donc de pouvoir lier un champ du form à un attribut de l'entité
         */
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
