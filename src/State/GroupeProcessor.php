<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;

class GroupeProcessor implements ProcessorInterface
{
    public function __construct(
        public readonly EntityManagerInterface $entityManager,
    ){}

    public function process(
        mixed $data, 
        Operation $operation, 
        array $uriVariables = [], 
        array $context = []
    ): void
    {
        if(false === $data instanceof Groupe) {
            return;
        }

        if($operation->getName() === 'groupe-create') {
            $data->setCreatedAt(new \DateTimeImmutable());
        }

        if($operation->getName() === 'groupe-modify') {
            $data->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        if($operation->getName() === 'groupe-remove') {
            $this->entityManager->remove($data);
            $this->entityManager->flush();
        }
    }
}