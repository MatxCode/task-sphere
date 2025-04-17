<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProjectRepository $projectRepo
    ){
    }

    public function getProjectsList(User $user): array
    {
        $projects = [];
        foreach ($user->getProjects() as $project) {
            $projects[$project->getKeyCode()] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'keyCode' => $project->getKeyCode(),
                'lead' => $project->getLeadUser()->getUsername(),
            ];
        }
        return $projects;
    }

    public function findOneByKeyCode(string $keyCode)
    {
        return $this->projectRepo->findOneBy(['keyCode' => $keyCode]);
    }

    public function remove(Project $project): void
    {
        // 1. Mettre à jour tous les utilisateurs qui ont ce projet comme selected_project
        $users = $this->em->getRepository(User::class)
            ->findBy(['selectedProject' => $project]);

        foreach ($users as $user) {
            $user->setSelectedProject(null);
        }

        // 2. Supprimer toutes les issues associées
        foreach ($project->getIssues() as $issue) {
            $this->em->remove($issue);
        }

        // 3. Supprimer le projet
        $this->em->flush(); // Optionnel: flush intermédiaire
        $this->em->remove($project);
        $this->em->flush();
    }
}