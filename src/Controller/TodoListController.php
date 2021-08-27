<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use App\Repository\TodoListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Doctrine\Persistence\ManagerRegistry;

/**
 * @property ManagerRegistry em
 */
class TodoListController extends AbstractController
{
    public function __construct(ManagerRegistry $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="todo_list")
     * @param TodoListRepository $tr
     * @return Response
     */
    public function index(TodoListRepository $tr): Response
    {
        return $this->render('todo_list/index.html.twig',[
            'TodoLists' => $tr->findAll()
        ]);
    }

    /**
     * @Route("/edit-{id}", name="todo_list_edit", methods="GET|POST")
     * @param TodoList $todoList
     * @param Request $request
     * @return Response
     */
    public function edit(TodoList $todoList, Request $request): Response
    {
        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','TodoList éditée avec succès');
            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo_list/edit.html.twig', [
            'todoList' => $todoList,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="todo_list_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $todoList = new TodoList();
        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todoList);
            $em->flush();
            $this->addFlash('success','TodoList créée avec succès');
            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo_list/new.html.twig', [
            'todoList' => $todoList,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-{id}", name="todo_list_delete", methods="DELETE")
     */
    public function delete(TodoList $todoList, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $todoList->getID(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($todoList);
            $em->flush();
            $this->addFlash('success','TodoList supprimée avec succès');
        }
        return $this->redirectToRoute('todo_list');
    }
}
