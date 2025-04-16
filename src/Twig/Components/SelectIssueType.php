<?php

namespace App\Twig\Components;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class SelectIssueType
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;
}