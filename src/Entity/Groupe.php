<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\GroupeRepository;
use App\State\GroupeProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            openapiContext: [
                'summary' => 'Public Endpoint : get groups names',
            ],
            name: 'groupes', 
            uriTemplate: '/groupes', 
            normalizationContext: [
                'groups' => ['groupe:get_collection:read'],
            ],
        ),
        new GetCollection(
            openapiContext: [
                'summary' => 'Public Endpoint : get one groups names and users in it (firsname, lastname)',
            ],
            name: 'groupe-users', 
            uriTemplate: '/groupes/users', 
            normalizationContext: [
                'groups' => ['groupeusers:get_collection:read'],
            ],
        ),
        new Get(
            openapiContext: [
                'summary' => 'Public Endpoint : get one group name',
            ],
            normalizationContext: [
                'groups' => ['groupe:get:read'],
            ],
        ),
        new Post(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : create a new group',
            ],
            name: 'groupe-create',
            processor: GroupeProcessor::class,
            uriTemplate: '/groupes/create',
            normalizationContext: [
                'groups' => ['groupe:post:read'],
            ],
            denormalizationContext: [
                'groups' => ['groupe:post:write'],
            ],
        ),
        new Put(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : modify an existing group',
            ],
            name: 'groupe-modify',
            processor: GroupeProcessor::class,
            uriTemplate: '/groupes/{id}/modify',
            normalizationContext: [
                'groups' => ['groupe:put:read'],
            ],
            denormalizationContext: [
                'groups' => ['groupe:put:write'],
            ],
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : remove an existing group',
            ],
            name: 'groupe-remove', 
            processor: GroupeProcessor::class,
            uriTemplate: '/groupes/{id}/remove',
            normalizationContext: [
                'groups' => ['groupe:delete:read'],
            ],
            denormalizationContext: [
                'groups' => ['groupe:delete:write'],
            ],
        ),
    ]
)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'groupe:post:write', 
        'groupe:post:read', 
        'groupe:put:read', 
        'groupe:put:write', 
        'groupe:get:read', 
        'groupe:get_collection:read', 
        'groupeusers:get_collection:read'
    ])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups([
        'groupe:post:write',
        'groupe:post:read',
    ])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['groupe:put:write'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'groupes', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    #[Groups(['groupeusers:get_collection:read'])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGroupes($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGroupes() === $this) {
                $user->setGroupes(null);
            }
        }

        return $this;
    }
}
