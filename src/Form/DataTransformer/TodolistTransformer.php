<?php

namespace App\Form\DataTransformer;

use App\Entity\TodoList;
use App\Repository\TodoListRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TodolistTransformer implements DataTransformerInterface
{
    private $repository;

    public function __construct(TodoListRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function transform($value): ?int
    {
        return ( $value !== null ) ? $value->getId() : null;
    }

    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value): ?TodoList
    {
        if ($value === null) {
            return null;
        }
        return $this->repository->find($value);
    }
}
