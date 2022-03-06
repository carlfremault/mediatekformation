<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe métier qui représente un niveau.
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * Identifiant d'un niveau. Généré automatiquement.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Libellé d'un niveau. Ne peut pas être vide.
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $libelle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
