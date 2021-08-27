<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Done;

    /**
     * @ORM\ManyToOne(targetEntity=TodoList::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $TodoList;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDone(): ?bool
    {
        return $this->Done;
    }

    public function setDone(bool $Done): self
    {
        $this->Done = $Done;

        return $this;
    }

    public function getTodoList(): ?TodoList
    {
        return $this->TodoList;
    }

    public function setTodoList(?TodoList $TodoList): self
    {
        $this->TodoList = $TodoList;

        return $this;
    }
}
