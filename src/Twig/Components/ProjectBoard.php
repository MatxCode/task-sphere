<?php

namespace App\Twig\Components;

use App\Entity\Issue;
use App\Service\IssueService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ProjectBoard
{
    /** @var Issue[] */
    public array $readyIssues = [];

    /** @var Issue[] */
    public array $inProgressIssues = [];

    /** @var Issue[] */
    public array $resolvedIssues = [];

    public function __construct(
        private readonly IssueService $issueService,
    ){
    }

    public function mount(): void
    {
        $this->readyIssues = $this->issueService->getReadyIssues();
        $this->inProgressIssues = $this->issueService->getInProgressIssues();
        $this->resolvedIssues = $this->issueService->getResolvedIssues();
    }
}
