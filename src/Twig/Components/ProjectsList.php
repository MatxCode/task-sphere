<?php

namespace App\Twig\Components;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ProjectsList
{
    use DefaultActionTrait;
    #[LiveProp]
    public array $projects = [];
}