<?php

namespace App\Controller;

use App\Form\Type\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(): Response
    {
        $form = $this->createForm(RegisterType::class);

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
