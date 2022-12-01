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
            normalizationContext: ['groups' => ['get_groupes']],
        ),
        new GetCollection(
            openapiContext: [
                'summary' => 'Public Endpoint : get groups names and users informations',
            ],
            name: 'groupe-users', 
            uriTemplate: '/groupes/users', 
            normalizationContext: [
                'groups' => ['get_groupes_users'],
            ],
        ),
        new Get(
            openapiContext: [
                'summary' => 'Public Endpoint : get one group name',
            ],
            normalizationContext: ['groups' => ['get_groupe']],
        ),
        new Post(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : create a new group',
            ],
            name: 'groupe-create',
            uriTemplate: '/groupes/create',
            processor: GroupeProcessor::class,
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : Only Admin can access to this URL.',
            normalizationContext: ['groups' => ['admin_get_add']],
            denormalizationContext: ['groups' => ['admin_add_groupe']],
        ),
        new Put(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : modify an existing group',
            ],
            name: 'groupe-modify',
            uriTemplate: '/groupes/{id}/modify',
            processor: GroupeProcessor::class,
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : Only Admin can access to this URL.',
            normalizationContext: ['groups' => ['admin_get_modify']],
            denormalizationContext: ['groups' => ['admin_modify_groupe']],
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : remove an existing group',
            ],
            name: 'groupe-remove',
            uriTemplate: '/groupes/{id}/remove',
            processor: GroupeProcessor::class,
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : Only Admin can access to this URL.',
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
        'get_groupe',
        'get_groupes',
        'get_groupes_users',
        'admin_add_groupe',
        'admin_get_add',
        'admin_modify_groupe',
        'admin_get_modify',
    ])]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'groupes', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    #[Groups(['get_groupes_users'])]
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
