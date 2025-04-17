<?php

namespace App\Twig\Components;

use App\Entity\Issue;
use App\Enum\IssueType;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class SelectIssueType
{
    use DefaultActionTrait;

    #[LiveProp]
    public Issue $issue;

    /** @var IssueType[] */
    #[LiveProp]
    public array $types = [];
}
