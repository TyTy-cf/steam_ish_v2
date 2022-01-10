<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'account.index.table.name',
                'attr' => [
                    'class' => 'bg-dark'
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'account.index.table.email',
                'attr' => [
                    'class' => 'bg-dark'
                ]
            ])
            ->add('nickname', TextType::class, [
                'label' => 'account.index.table.nickname',
                'required' => false,
                'attr' => [
                    'class' => 'bg-dark'
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
            'data_class' => Account::class,
        ]);
    }
}
