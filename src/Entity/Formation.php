<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Classe métier qui représente une formation.
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation {

    /**
     * Largeur des images 'miniature'.
     * @var int
     */
    private const MINIATUREWIDTH = 120;

    /**
     * Hauteur des images 'miniature'.
     * @var int
     */
    private const MINIATUREHEIGHT = 90;

    /**
     * Largeur des images 'picture'.
     * @var int
     */
    private const PICTUREWIDTH = 640;

    /**
     * Hauteur des images 'picture'.
     * @var int
     */
    private const PICTUREHEIGHT = 480;

    /**
     * Identifiant d'une formation. Généré automatiquement.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Date de parution d'une formation. Ne peut pas être vide.
     * @Assert\NotBlank
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $publishedAt;

    /**
     * Titre d'une formation. Ne peut pas être vide.
     * @Assert\NotBlank
     * @Assert\Length(max=91 ,maxMessage = "Le titre ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=91, nullable=false)
     */
    private $title;

    /**
     * Description d'une formation. Peut être vide.
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * URL qui pointe vers l'image 'miniature' d'une formation, affiché sur les pages qui listent les formations. Peut être vide.
     * @Assert\Length(max=46,maxMessage = "L'url de la miniature ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $miniature;

    /**
     * URL qui pointe vers l'image 'picture' d'une formation, affiché sur la page qui affiche les détails d'une formation.
     * @Assert\Length(max=48,maxMessage = "L'url de l'image ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=48, nullable=true)
     */
    private $picture;

    /**
     * Identifiant de la vidéo sur YouTube. 11 caractères, peut être vide.
     * @Assert\Length(max=11,maxMessage = "L'identifiant de la vidéo ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $videoId;

    /**
     * Le niveau d'une formation.
     * @ORM\ManyToOne(targetEntity=Niveau::class)
     */
    private $niveau;

    /**
     * Getter pour l'identifiant
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Getter pour la date de publication d'une formation sous format DateTime
     * @return DateTimeInterface|null
     */
    public function getPublishedAt(): ?DateTimeInterface {
        return $this->publishedAt;
    }

    /**
     * Getter pour la date de publication d'une formation sous format de chaine de caractères
     * @return string
     */
    public function getPublishedAtString(): string {
        return $this->publishedAt->format('d/m/Y');
    }

    /**
     * Setter pour la date de publication d'une formation
     * @param DateTimeInterface|null $publishedAt
     * @return \self
     */
    public function setPublishedAt(?DateTimeInterface $publishedAt): self {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Getter pour le titre d'une formation
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * Setter pour le titre d'une formation
     * @param string|null $title
     * @return \self
     */
    public function setTitle(?string $title): self {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter pour la description d'une formation
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * Setter pour la description d'une formation
     * @param string|null $description
     * @return \self
     */
    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter pour l'url de la miniature d'une formation
     * @return string|null
     */
    public function getMiniature(): ?string {
        return $this->miniature;
    }

    /**
     * Setter pour l'url de la miniature d'une formation
     * @param string|null $miniature
     * @return \self
     */
    public function setMiniature(?string $miniature): self {
        $this->miniature = $miniature;

        return $this;
    }

    /**
     * Getter pour l'url de l'image d'une formation
     * @return string|null
     */
    public function getPicture(): ?string {
        return $this->picture;
    }

    /**
     * Setter pour l'url de l'image d'une formation
     * @param string|null $picture
     * @return \self
     */
    public function setPicture(?string $picture): self {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Getter pour l'identifiant de la vidéo d'une formation
     * @return string|null
     */
    public function getVideoId(): ?string {
        return $this->videoId;
    }

    /**
     * Setter pour l'identifiant de la vidéo d'une formation
     * @param string|null $videoId
     * @return \self
     */
    public function setVideoId(?string $videoId): self {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Getter pour le niveau d'une formation sous forme de Niveau
     * @return \App\Entity\niveau|null
     */
    public function getNiveau(): ?niveau {
        return $this->niveau;
    }

    /**
     * Getter pour le libellé du niveau d'une formation.
     * @return string|null
     */
    public function getNiveauString(): ?string {
        return $this->niveau->getLibelle();
    }

    /**
     * Getter pour l'identifiant du niveau d'une formation
     * @return int|null
     */
    public function getNiveauId(): ?int {
        return $this->niveau->getId();
    }

    /**
     * Setter pour le niveau d'une formation
     * @param \App\Entity\niveau|null $niveau
     * @return \self
     */
    public function setNiveau(?niveau $niveau): self {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Cette fonction vérifie, quand un Url pour une image a été saisie, s'il s'agit bien d'une image,
     * puis si sa taille correspond avec les tailles fixées en constantes PICTUREWIDTH, PICTUREHEIGHT*
     * pour les 'picture', MINIATUREWIDTH, MINIATUREHEIGHT pour les miniatures
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param type $payload
     */
    public function validate(ExecutionContextInterface $context) {
        $miniatureUrl = $this->getMiniature();
        $miniatureWidth = 0;
        $miniatureHeight = 0;

        $pictureUrl = $this->getPicture();
        $pictureWidth = 0;
        $pictureHeight = 0;

        if ($miniatureUrl != "") {
            try {
                $miniatureUrlurl = 'http://'.$miniatureUrl;
                $info = getimagesize($miniatureUrlurl);
            } catch (\Exception $ex) {
                $context->buildViolation("Ceci n'est pas une image")
                        ->atPath('miniature')
                        ->addViolation();
                return;
            }

            if ($info) {
                $miniatureWidth = $info[0];
                $miniatureHeight = $info[1];
                if ($miniatureWidth != self::MINIATUREWIDTH || $miniatureHeight != self::MINIATUREHEIGHT) {
                    $context->buildViolation("Les miniatures doivent être de taille " . self::MINIATUREWIDTH . "x" . self::MINIATUREHEIGHT . " pixels")
                            ->atPath('miniature')
                            ->addViolation();
                }
            } else {
                $context->buildViolation("Ceci n'est pas une image")
                        ->atPath('miniature')
                        ->addViolation();
            }
        }

        if ($pictureUrl != "") {
            try {
                $pictureUrl = 'http://'.$pictureUrl;
                $info = getimagesize($pictureUrl);
            } catch (\Exception $ex) {
                $context->buildViolation("Ceci n'est pas une image")
                        ->atPath('picture')
                        ->addViolation();
                return;
            }

            if ($info) {
                $pictureWidth = $info[0];
                $pictureHeight = $info[1];
                if ($pictureWidth > self::PICTUREWIDTH || $pictureHeight > self::PICTUREHEIGHT) {
                    $context->buildViolation("La taille des images ne doit pas dépasser " . self::PICTUREWIDTH . "x" . self::PICTUREHEIGHT . " pixels")
                            ->atPath('picture')
                            ->addViolation();
                }
            } else {
                $context->buildViolation("Ceci n'est pas une image")
                        ->atPath('picture')
                        ->addViolation();
            }
        }
    }

}
