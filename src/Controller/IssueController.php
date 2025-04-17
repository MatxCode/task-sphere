<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Issue;
use App\Entity\User;
use App\Enum\IssueStatus;
use App\Form\Type\IssueType;
use App\Service\AttachmentService;
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
        if(!$issue)
        {
            return $this->redirectToRoute('issue_list');
        }
        return $this->render('issue/show.html.twig', [
            'issue' => $issue,
            'statuses' => IssueStatus::cases(),
            'types' => \App\Enum\IssueType::cases(),
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
        $form = $this->createForm(IssueType::class, $issue, [
            'project' => $this->getUser()->getSelectedProject()
        ]);
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

    #[Route('/issue/{id}/update-summary', name: 'issue_update_summary', methods: ['POST'])]
    public function updateSummary(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $issue->setSummary($data['summary'] ?? '');
        $em->flush();

        return $this->json(['success' => true, 'summary' => $issue->getSummary()]);
    }

    #[Route('/issue/{id}/update-description', name: 'issue_update_description', methods: ['POST'])]
    public function updateDescription(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $issue->setDescription($data['description'] ?? '');
        $em->flush();

        return $this->json(['success' => true, 'description' => $issue->getDescription()]);
    }

    #[Route('/issue/{id}/add-attachment', name: 'issue_add_attachment', methods: ['POST'])]
    public function addAttachment(Request $request, Issue $issue, AttachmentService $attachmentService, EntityManagerInterface $em): Response
    {
        $attachment = $attachmentService->handleUploadedAttachment($issue, $request);

        return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
    }

    #[Route('/attachment/{id}/delete', name: 'issue_delete_attachment', methods: ['POST'])]
    public function deleteAttachment(Attachment $attachment, EntityManagerInterface $em): Response
    {
        $issueId = $attachment->getIssue()->getId();
        $em->remove($attachment);
        $em->flush();

        return $this->redirectToRoute('issue_show', ['id' => $issueId]);
    }

    #[Route('/issue/{id}/update_type', name: 'update_issue_type', methods: ['POST'])]
    public function updateType(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $type = \App\Enum\IssueType::from($request->request->get('type'));

        $issue->setType($type);

        $em->flush();

        return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
    }

    #[Route('/issue/{id}/update_status', name: 'update_issue_status', methods: ['POST'])]
    public function updateStatus(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $status = IssueStatus::from($request->request->get('status'));

        $issue->setStatus($status);

        $em->flush();

        return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
    }

    #[Route('/issue/{id}/update_assignee', name: 'update_issue_assignee', methods: ['POST'])]
    public function updateAssignee(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $assigneeId = $request->request->get('assignee');

        if (empty($assigneeId)) {
            $issue->setAssignee(null);
        } else {
            $assignee = $em->getRepository(User::class)->find($assigneeId);
            if (!$assignee) {
                throw $this->createNotFoundException('Utilisateur non trouvé');
            }
            $issue->setAssignee($assignee);
        }

        $em->flush();

        return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
    }

    #[Route('/issue/{id}/update-story-points', name: 'update_story_points', methods: ['POST'])]
    public function updateStoryPoints(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $storyPoints = $request->request->get('storyPoints');

        $issue->setStoryPointEstimate($storyPoints);
        $em->flush();

        return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
    }

    #[Route('/issue/{id}/update_reporter', name: 'update_issue_reporter', methods: ['POST'])]
    public function updateReporter(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $reporterId = $request->request->get('reporter');

        if (empty($reporterId)) {
            $issue->setReporter(null);
        } else {
            $reporter = $em->getRepository(User::class)->find($reporterId);
            if (!$reporter) {
                throw $this->createNotFoundException('Utilisateur non trouvé');
            }
            $issue->setReporter($reporter);
        }

        $em->flush();

        return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
    }

    #[Route('/issue/{id}/update_status_ajax', name: 'update_issue_status_ajax', methods: ['POST'])]
    public function updateStatusAjax(Request $request, Issue $issue, EntityManagerInterface $em): Response
    {
        $status = $request->request->get('status');

        try {
            $issue->setStatus(\App\Enum\IssueStatus::from($status));
            $em->flush();

            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

}
