<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Classe métier qui représente un utilisateur.
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface {

    /**
     * Identifiant d'un utilisateur. Généré automatiquement.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Nom d'un utilisateur.
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * Rôle(s) d'un utilisateur.
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * Mot de passe d'un utilisateur. Hashé.
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): string {
        return (string) $this->username;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array {
        $rolesUser = $this->roles;
        // guarantee every user at least has ROLE_USER
        $rolesUser[] = 'ROLE_USER';

        return array_unique($rolesUser);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * Fonction redéfinie obligatoirement suite à l'implémentation de UserInterface. Pas utilisée.
     * @return string|null
     */
    public function getSalt(): ?string {
        return null;
    }

    /**
     * Fonction redéfinie obligatoirement suite à l'implémentation de UserInterface. Pas utilisée.
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
