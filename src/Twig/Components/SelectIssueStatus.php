<?php

namespace App\Twig\Components;

use App\Entity\Issue;
use App\Enum\IssueStatus;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class SelectIssueStatus
{
    use DefaultActionTrait;

    public Issue $issue;

    /** @var IssueStatus[] */
    public array $statuses = [];
}
