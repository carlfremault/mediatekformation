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

    /**
     * Getter pour l'identifiant d'un niveau
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter pour le libellé d'un niveau
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * Setter pour le libellé d'un niveau
     * @param string|null $libelle
     * @return \self
     */
    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
