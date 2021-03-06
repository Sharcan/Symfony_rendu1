<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ["placeholder" => "Nom de la tâche"]
            ])

            ->add('deadline', DateType::class, [
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jours',
                ],
                'format' => 'dd-MM-yyyy',
            ])

            // ->add('state')

            // ->add('user_id', TextType::class, [
            //     'label' => false,
            //     'attr' => ["placeholder" => "Quel utilisateur viser ?"]
            // ])
            
            ->add('submit',SubmitType::class, [
                'label' => 'Créer',
                'attr'=> ["class" => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
