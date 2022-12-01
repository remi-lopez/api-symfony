<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use App\State\UserProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            openapiContext: [
                'summary' => 'Public Endpoint : get user\'s firstname and lastname',
            ],
            normalizationContext: [
                'groups' => ['user:get_collection:read'],
            ],
        ),
        new GetCollection(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : all users informations'
            ],
            name: 'admin-users',
            uriTemplate: '/users/all',
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : Only Admin can access to this URL.',
            normalizationContext: [
                'groups' => ['adminuser:get_collection:read'],
            ],
        ),
        new Get(
            security: 'is_granted("ROLE_USER" or "ROLE_ADMIN")',
            openapiContext: [
                'summary' => 'Private Endpoint - User : user\'s informations',
            ],
            normalizationContext: [
                'groups' => ['user:get:read'],
            ],
        ),
        new Post(
            openapiContext: [
                'summary' => 'Public Endpoint - Register : user\'s email, firstname, lastname and password',
            ],
            name: 'register', 
            uriTemplate: '/register',
            processor: UserProcessor::class,
            normalizationContext: [
                'groups' => ['register:post:read'],
            ],
            denormalizationContext: [
                'groups' => ['register:post:write'],
            ],
        ),
        new Post(
            openapiContext: [
                'summary' => 'Private Endpoint - User : add group for one user',
            ],
            name: 'user-addgroupe', 
            uriTemplate: '/users/{id}/addgroupe',
            security: 'is_granted("ROLE_USER" or "ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : You can\'t access to this URL.',
            normalizationContext: [
                'groups' => ['addgroupe:post:read'],
            ],
            denormalizationContext: [
                'groups' => ['addgroupe:post:write'],
            ],
        ),
        new Put(
            openapiContext: [
                'summary' => 'Private Endpoint - User / Admin : modify user\'s informations',
            ],
            name: 'user-update',
            processor: UserProcessor::class,
            uriTemplate: '/users/{id}/update',
            security: 'is_granted("ROLE_USER" or "ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : You can\'t access to this URL.',
            normalizationContext: [
                'groups' => ['user:put:read'],
            ],
            denormalizationContext: [
                'groups' => ['user:put:write'],
            ],
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : remove user',
            ],
            name: 'user-remove',
            processor: UserProcessor::class,
            uriTemplate: '/users/{id}/remove',
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : Only Admin can access to this URL.',
            normalizationContext: [
                'groups' => ['user:delete:read'],
            ],
            denormalizationContext: [
                'groups' => ['user:delete:write'],
            ],
        )
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups([
        'register:post:write', 
        'register:post:read',
        'user:put:write', 
        'adminuser:get_collection:read',
        'user:get:read'
    ])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups([
        'adminuser:get_collection:read',
    ])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[SerializedName('password')]
    #[Groups([
        'register:post:write',
        'update:put:write',
    ])]
    private $plainPassword;

    #[ORM\Column(length: 255)]
    #[Groups([
        'register:post:write', 
        'register:post:read', 
        'update:put:write',
        'user:get_collection:read',
        'adminuser:get_collection:read',
        'user:get:read'
    ])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'register:post:write', 
        'register:post:read',
        'update:put:write',
        'user:get_collection:read',
        'adminuser:get_collection:read',
        'user:get:read'
    ])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups([
        'register:post:write', 
        'register:post:read',
        'update:put:write', 
        'addgroupe:post:write',
        'adminuser:get_collection:read',
        'user:get:read', 
    ])]
    private ?Groupe $groupes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGroupes(): ?Groupe
    {
        return $this->groupes;
    }

    public function setGroupes(?Groupe $groupes): self
    {
        $this->groupes = $groupes;

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
}
