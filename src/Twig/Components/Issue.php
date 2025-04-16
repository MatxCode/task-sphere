<?php

namespace App\Twig\Components;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[AsLiveComponent]
class Issue
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['description', 'summary'])]
    #[Assert\Valid]
    public \App\Entity\Issue $issue;

    #[LiveProp]
    public bool $isEditingSummary = false;

    #[LiveProp]
    public bool $isEditingDescription = false;

    public function __construct(
        private readonly EntityManagerInterface $em,
    ){
    }

    #[LiveAction]
    public function activateEditingSummary(): void
    {
        $this->isEditingSummary = true;
    }

    #[LiveAction]
    public function activateEditingDescription(): void
    {
        $this->isEditingDescription = true;
    }
    #[LiveAction]
    public function saveSummary(): void
    {
        $this->validate();
        $this->isEditingSummary = false;
        $this->em->flush();
    }

    #[LiveAction]
    public function saveDescription(): void
    {
        $this->validate();
        $this->isEditingDescription = false;
        $this->em->flush();
    }
}