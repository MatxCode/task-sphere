<?php

namespace App\Form\Type;

use App\Entity\Issue;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\IssueStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'label' => 'Projet',
            ])
            ->add('type', EnumType::class, [
                'choice_label' => fn(\App\Enum\IssueType $type) => $type->label(),
                'class' => \App\Enum\IssueType::class,
                'label' => 'Type',
            ])
            ->add('status', EnumType::class, [
                'choice_label' => fn(IssueStatus $status) => $status->label(),
                'class' => IssueStatus::class,
                'label' => 'Statut',
            ])
            ->add('summary', TextType::class, [
                'label' => 'Résumé',
            ])
            ->add('assignee', EntityType::class, [
                'class' => User::class,
                'label' => 'Assigné',
            ])
            ->add('reporter', EntityType::class, [
                'class' => User::class,
                'label' => 'Rapporteur',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
