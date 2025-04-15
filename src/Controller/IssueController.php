<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\Project;
use App\Entity\User;
use App\Form\Type\IssueType;
use App\Form\Type\ProjectType;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IssueController extends AbstractController
{
    #[Route('/issues/{id}', name: 'issue_show')]
    public function show(?Issue $issue): Response
    {
        return $this->render('issue/show.html.twig', [
            'issue' => $issue,
        ]);
    }
    #[Route('/issues', name: 'issue_list')]
    public function list(): Response
    {
        return $this->render('issue/list.html.twig', [
            'controller_name' => 'IssueController',
        ]);
    }

    #[Route('/issue/create', name: 'issue_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $issue = new Issue();
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($issue);
            $em->flush();

            return $this->redirectToRoute('issue_show', [
                'id' => $issue->getId(),
            ]);
        }

        // Gestion des erreurs
        dd($form->getErrors(true));
    }

    #[Route('/issue/{id}/delete', name: 'issue_delete', methods: ['POST'])]
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
