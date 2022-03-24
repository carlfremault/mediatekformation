<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Classe métier qui représente un utilisateur
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * Identifiant de l'utilisateur. Généré automatiquement
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Adresse email de l'utilisateur
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * Rôle de l'utilisateur
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * Mot de passe de l'utilisateur. Pas utilisé
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * Identifiant Keycloak de l'utilisateur
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keycloakId;

    /**
     * Getter pour l'identifiant de l'utilisateur
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter pour l'adresse email de l'utilisateur
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter pour l'adresse email de l'utilisateur
     * @param string $email
     * @return \self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Getter pour le username  de l'utilisateur
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter pour les roles  de l'utilisateur
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $userRoles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $userRoles[] = 'ROLE_USER';
        return array_unique($userRoles);
    }

    /**
     * Setter pour les roles de l'utilisateur
     * @param array $roles
     * @return \self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Getter pour le mot de passe de l'utilisateur (non utilisé)
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setter pour le mot de passe de l'utilisateur (non utilisé)
     * @param string $password
     * @return \self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Fonction pour vider des données sensibles stockées temporairement. Non utilisé
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * Getter pour l'identifiant Keycloak de l'utilisateur
     * @return string|null
     */
    public function getKeycloakId(): ?string
    {
        return $this->keycloakId;
    }

    /**
     * Setter pour l'identifiant Keycloak de l'utilisateur
     * @param string|null $keycloakId
     * @return \self
     */
    public function setKeycloakId(?string $keycloakId): self
    {
        $this->keycloakId = $keycloakId;

        return $this;
    }
}
