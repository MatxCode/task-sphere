<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class SelectIssueReporter
{
    use DefaultActionTrait;

    public \App\Entity\Issue $issue;
    public array $people = [];
}
