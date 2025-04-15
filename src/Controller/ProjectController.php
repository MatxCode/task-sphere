<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\Type\ProjectType;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    ){
    }

    #[Route('/projects/{keyCode}', name: 'project_show')]
    public function show(?Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/projects', name: 'project_list')]
    public function list(): Response
    {
        return $this->render('project/list.html.twig', [
            'projects' => $this->projectService->getProjectsList($this->getUser()),
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

            /** @var User $user */
            $user = $this->getUser();
            $user->addProject($project);
            $em->flush();

            return $this->redirectToRoute('project_show', [
                'keyCode' => $project->getKeyCode()
            ]);
        }

        // Gestion des erreurs
        dd($form->getErrors(true));
    }

    #[Route('/project/{keyCode}/delete', name: 'project_delete', methods: ['POST'])]
    public function delete(Request $request, string $keyCode, ProjectService $projectService): Response
    {
        $project = $projectService->findOneByKeyCode($keyCode);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        if ($this->isCsrfTokenValid('delete'.$keyCode, $request->request->get('_token'))) {
            $projectService->remove($project);
            $this->addFlash('success', 'Project deleted successfully');
        }

        return $this->redirectToRoute('project_list');
    }
}
