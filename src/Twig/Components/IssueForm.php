<?php

namespace App\Twig\Components;

use App\Entity\Issue;
use App\Entity\Project;
use App\Form\Type\IssueType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class IssueForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public ?Project $project = null;

    #[LiveProp]
    public ?Issue $initialFormData = null;

    public function mount(): void
    {
        $this->project = $this->getUser()->getSelectedProject();
        $this->initialFormData = new Issue();
    }

    protected function instantiateForm(): FormInterface
    {
        if (null === $this->project) {
            throw new \RuntimeException('No project selected');
        }

        return $this->createForm(IssueType::class, $this->initialFormData, [
            'project' => $this->project
        ]);
    }


//    #[LiveAction]
//    public function save(EntityManagerInterface $em): Response
//    {
//        $this->validate();
//
//        $this->submitForm();
//
//        /** @var Issue $issue */
//        $issue = $this->getForm()->getData();
//
//        $em->persist($issue);
//        $em->flush();
//
//        return $this->redirectToRoute('issue_show', [
//            'id' => $issue->getId(),
//        ]);
//    }
}