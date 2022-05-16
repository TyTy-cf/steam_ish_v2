<?php


namespace App\Form\Api;


use Drosalys\Bundle\ApiBundle\Filter\ApiFilter;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GameFilterFormType.php
 *
 * @author Kevin Tourret
 */
class GameFilterFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityFilterType::class, [
                'choice_name' => 'name',
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])
            ->add('genres', EntityFilterType::class, [
                'choice_name' => 'name',
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])
        ;
    }

    public function getParent(): string
    {
        return ApiFilter::class;
    }

}
