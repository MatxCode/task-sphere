<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\Type\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/projects/{keyCode}', name: 'project_show')]
    public function show(?Project $project): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

    #[Route('/project/create', name: 'project_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setLeadUser($this->getUser());
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_show', [
                'keyCode' => $project->getKeyCode()
            ]);
        }

        // Gestion des erreurs
        dd($form->getErrors(true));
    }
}
