<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ProjectRepository;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepo
    ){
    }

    public function getProjectsList(User $user): array
    {
        $projects = [];
        foreach ($user->getProjects() as $project) {
            $projects[$project->getId()] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'keyCode' => $project->getKeyCode(),
                'lead' => $project->getLeadUser()->getUsername(),
            ];
        }
        return $projects;
    }
}