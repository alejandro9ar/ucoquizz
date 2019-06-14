<?php

namespace App\Form;

use App\DTO\PlayerAnswerDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer1', CheckboxType::class,
                ['label' => 'Respuesta 1',
                    'required' => false, ])
            ->add('answer2', CheckboxType::class,
                ['label' => 'Respuesta 2',
                    'required' => false, ])
            ->add('answer3', CheckboxType::class,
                ['label' => 'Respuesta 3',
                    'required' => false, ])
            ->add('answer4', CheckboxType::class,
                ['label' => 'Respuesta 4',
                    'required' => false, ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PlayerAnswerDTO::class,
        ]);
    }
}
