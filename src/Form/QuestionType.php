<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Questionary;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                ['label' => 'Titulo'])

            ->add('description', TextType::class,
                ['label' => 'Descripcion'])

            ->add('answer1', TextType::class,
                ['label' => 'Respuesta 1'])

            ->add('check1')
            ->add('answer2', TextType::class,
                ['label' => 'Respuesta 2'])

            ->add('check2')
            ->add('answer3', TextType::class,
                ['label' => 'Respuesta 3'])

            ->add('check3')
            ->add('answer4', TextType::class,
                ['label' => 'Respuesta 4'])

            ->add('check4')
            ->add('duration')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
