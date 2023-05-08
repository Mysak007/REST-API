<?php

namespace App\Form;

use App\Model\UserFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('page', IntegerType::class)
            ->add('sortByColumn', ChoiceType::class, [
                'choices' => [
                    'nick' => 'nick',
                    'firstName' => 'firstName',
                    'lastName' => 'lastName'
                ]
            ])
            ->add('sortDirection', ChoiceType::class, [
                'choices' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserFilter::class,
            'csrf_protection' => false
        ]);
    }
}
