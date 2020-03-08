<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Users;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */


    public function index(EntityManagerInterface $entityManager, Request $request)
    {

        $task = new Task();
        $formTask = $this->createForm(TaskType::class, $task);
        $formTask->handleRequest($request);
        
        $userRepository = $this->getDoctrine()
                        ->getRepository(Users::class);
                        

        $taskRepository = $this->getDoctrine()
                        ->getRepository(Task::class);

        if($formTask->isSubmitted() && $formTask->isValid()){
            $userId = $userRepository->find($request->request->get('userId'));
            $task = $formTask->getData();
            $task->setUserId($userId);
            $task->setState(false);

            $entityManager->persist($task);
            $entityManager->flush();

            
            // return $this->redirectToRoute('task');

        }

        $users = $userRepository->findAll();

        $tasks = $taskRepository->findAll();


        dump($tasks);
        dump($users);
        return $this->render('task/index.html.twig', [
            'taskFormulaire' => $formTask->createView(),
            'users' => $users,
            'tasks' => $tasks
        ]);     
    }


    /**
     * @Route("/task/update/{id}", name="updateTask")
     */

    public function update($id, EntityManagerInterface $entityManager, Request $request)
    {

        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $task = $taskRepository->find($id);


        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $task = $form->getData();
            
            $stateCheck = $taskRepository->find($request->request->get('state'));
            if($stateCheck == 'true'){
                $task->setState(true);
            }else{
                $task->setState(false);
            }
            
            $userId = $taskRepository->find($request->request->get('userId'));
            
            $task->setUserId($userId);
            
            dump($task);

            $entityManager->persist($task);
            $entityManager->flush();

        }

        return $this->render('task/update.html.twig', [
            'task' => $task,
            'users' => $users,
            'updateTaskFormulaire' => $form->createView()
        ]);

    }



    /**
     * @Route("/task/remove/{id}", name="removeTask")
     */

    public function remove($id, EntityManagerInterface $entityManager)
    {

        $task = $this->taskRepository = $this->getDoctrine()->getRepository(Task::class)->find($id);
        dump($task);

        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('users');

    }


}
