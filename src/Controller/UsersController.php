<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Users;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {


        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setCreatedAt(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
        }


        $userRepository = $this->getDoctrine()
        ->getRepository(Users::class)
        ->findAll();

        dump($userRepository);



        return $this->render('users/index.html.twig', [
            'userFormulaire' => $form->createView(),
            'userRepository' => $userRepository
        ]);
    
    
    }


    /**
     * @Route("/users/update/{id}", name="updateUser")
    */
    public function update($id, Request $request, EntityManagerInterface $entityManager)
    {

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([
            'user_id' => $id
        ]);

        dump($tasks);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
        }


        return $this->render('users/update.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'updateUserFormulaire' => $form->createView()
        ]);

    }

}
