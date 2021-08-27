<?php

namespace App\Form;

use App\Entity\Task;
use App\Form\DataTransformer\TodolistTransformer;
use App\Repository\TodoListRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * @var TodoListRepository
     */
    private $todoListRepository;

    public function __construct(TodoListRepository $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Description', TextareaType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description',
                ]
            ])
            ->add('Done', CheckboxType::class, [
                'label' => 'Terminée',
                'required' => false,
            ])
            ->add('TodoList', HiddenType::class)
        ;

        $builder->get('TodoList')->addModelTransformer(new TodolistTransformer($this->todoListRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
