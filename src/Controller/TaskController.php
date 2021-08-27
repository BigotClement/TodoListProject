<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks-{id}", name="tasks")
     */
    public function index(TodoList $todoList, Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $this->addFlash('success','Tâche créée avec succès');
            return $this->redirectToRoute('tasks', ['id' => $todoList->getId()]);
        }

        return $this->render('task/index.html.twig', [
            'todolist' => $todoList,
            'tasks' => $todoList->getTasks(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tasks/edit-{id}", name="tasks_edit", methods="GET|POST")
     */
    public function edit(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','Tâche éditée avec succès');
            return $this->redirectToRoute('tasks', ['id' => $task->getTodoList()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tasks/delete-{id}", name="tasks_delete", methods="DELETE")
     */
    public function delete(Task $task, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $task->getID(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
            $this->addFlash('success','Tâche supprimée avec succès');
        }
        return $this->redirectToRoute('tasks', ['id' => $task->getTodoList()->getId()]);
    }
}
