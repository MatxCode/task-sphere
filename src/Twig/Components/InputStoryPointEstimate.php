<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('InputStoryPointEstimate')]
class InputStoryPointEstimate
{
    use DefaultActionTrait;

    public \App\Entity\Issue $issue;
}