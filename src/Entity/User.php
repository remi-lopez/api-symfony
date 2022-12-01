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
            normalizationContext: ['groups' => ['get_users']],
        ),
        new GetCollection(
            openapiContext: [
                'summary' => 'Private Endpoint - Admin : all users informations'
            ],
            name: 'admin-users',
            uriTemplate: '/users/all',
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : Only Admin can access to this URL.',
            normalizationContext: ['groups' => ['admin_get_users']],
        ),
        new Get(
            openapiContext: [
                'summary' => 'Private Endpoint - User : user\'s informations',
            ],
            security: '(object == user and previous_object == user) or is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : You can\'t access to this URL if you\'re not logged in.',
            normalizationContext: ['groups' => ['get_user']],
        ),
        new Post(
            openapiContext: [
                'summary' => 'Public Endpoint - Register : user\'s email, firstname, lastname and password',
            ],
            name: 'register', 
            uriTemplate: '/register',
            processor: UserProcessor::class,
            normalizationContext: ['groups' => ['read_register']],
            denormalizationContext: ['groups' => ['write_register']],
        ),
        new Post(
            openapiContext: [
                'summary' => 'Private Endpoint - User : add group for one user',
            ],
            name: 'user-addgroupe', 
            uriTemplate: '/users/{id}/addgroupe',
            security: '(object == user and previous_object == user) or is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : You can\'t access to this URL.',
            normalizationContext: ['groups' => ['read_add_groupe']],
            denormalizationContext: ['groups' => ['add_groupe']],
        ),
        new Put(
            openapiContext: [
                'summary' => 'Private Endpoint - User / Admin : modify user\'s informations',
            ],
            name: 'user-update',
            processor: UserProcessor::class,
            uriTemplate: '/users/{id}/update',
            security: '(object == user and previous_object == user) or is_granted("ROLE_ADMIN")',
            securityMessage: 'ACCESS DENIED : You can\'t access to this URL.',
            normalizationContext: ['groups' => ['read_update']],
            denormalizationContext: ['groups' => ['update']],
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
        'get_user',
        'write_register',
        'read_register',
        'update',
        'admin_get_users',
    ])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups([
        'admin_get_users',
    ])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[SerializedName('password')]
    #[Groups([
        'write_register',
        'update',
    ])]
    private $plainPassword;

    #[ORM\Column(length: 255)]
    #[Groups([
        'get_user',
        'get_users',
        'write_register',
        'read_register',
        'read_add_groupe',
        'update',
        'admin_get_users',
        'get_groupes_users',
    ])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'get_user',
        'get_users',
        'write_register',
        'read_register',
        'read_add_groupe',
        'update',
        'admin_get_users',
        'get_groupes_users',
    ])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups([
        'get_user',
        'write_register',
        'read_register',
        'update',
        'add_groupe',
        'read_add_groupe',
        'admin_get_users',
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
