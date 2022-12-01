<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        public readonly EntityManagerInterface $entityManager,
        public readonly UserPasswordHasherInterface $passwordHasher,
    ){}

    public function process(
        mixed $data, 
        Operation $operation, 
        array $uriVariables = [], 
        array $context = []
    ): void
    {
        if(false === $data instanceof User) {
            return;
        }

        if($operation->getName() === 'user-update') {
            $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPlainPassword() ?? $data->getPassword()));
            $data->eraseCredentials();
            $data->setUpdatedAt(new \DateTimeImmutable());
        }

        if($operation->getName() === 'register') {
            $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPlainPassword() ?? $data->getPassword()));
            $data->eraseCredentials();
            $data->setCreatedAt(new \DateTimeImmutable());
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        if($operation->getName() === 'user-remove') {
            $this->entityManager->remove($data);
            $this->entityManager->flush();
        }
    }
}
