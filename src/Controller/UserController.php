<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id?}', name: 'user_show')]
    public function show(?User $user, Request $request, EntityManagerInterface $em): Response
    {
        $formUser = $user ?: $this->getUser();
        $form = $this->createForm(UserType::class, $formUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('user_show');
        }

        return $this->render('user/show.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}