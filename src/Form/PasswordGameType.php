<?php
// src/Form/FileImportTypeype.php
namespace App\Form;

use App\DTO\GameDisponible;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('passwordgame', PasswordType::class,
                ['label' => 'Contraseña de juego'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameDisponible::class,
        ]);
    }
}
