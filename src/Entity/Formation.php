<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation {

    /**
     * @var int
     */
    private const MINIATUREWIDTH = 120;
    
    /**
     * @var int
     */
    private const MINIATUREHEIGHT = 90;
    
    /**
     * @var int
     */
    private const PICTUREWIDTH = 640;
    
    /**
     * @var int
     */
    private const PICTUREHEIGHT = 480;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $publishedAt;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=91 ,maxMessage = "Le titre ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=91, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Assert\Length(max=46,maxMessage = "L'url de la miniature ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $miniature;

    /**
     * @Assert\Length(max=48,maxMessage = "L'url de l'image ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=48, nullable=true)
     */
    private $picture;

    /**
     * @Assert\Length(max=11,maxMessage = "L'identifiant de la vidéo ne peut pas excéder {{ limit }} caractères")
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $videoId;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class)
     */
    private $niveau;

    public function getId(): ?int {
        return $this->id;
    }

    public function getPublishedAt(): ?DateTimeInterface {
        return $this->publishedAt;
    }

    public function getPublishedAtString(): string {
        return $this->publishedAt->format('d/m/Y');
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): self {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getMiniature(): ?string {
        return $this->miniature;
    }

    public function setMiniature(?string $miniature): self {
        $this->miniature = $miniature;

        return $this;
    }

    public function getPicture(): ?string {
        return $this->picture;
    }

    public function setPicture(?string $picture): self {
        $this->picture = $picture;

        return $this;
    }

    public function getVideoId(): ?string {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self {
        $this->videoId = $videoId;

        return $this;
    }

    public function getNiveau(): ?niveau {
        return $this->niveau;
    }

    public function getNiveauString(): ?string {
        return $this->niveau->getLibelle();
    }

    public function getNiveauId(): ?int {
        return $this->niveau->getId();
    }

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
    public function validate(ExecutionContextInterface $context, $payload) {
        $miniatureUrl = $this->getMiniature();
        $miniatureWidth = 0;
        $miniatureHeight = 0;

        $pictureUrl = $this->getPicture();
        $pictureWidth = 0;
        $pictureHeight = 0;

        if ($miniatureUrl != "") {
            try {
                $info = getimagesize($miniatureUrl);
                $miniatureWidth = $info[0];
                $miniatureHeight = $info[1];
            } catch (\Exception $e) {
                $context->buildViolation("Ceci n'est pas une image")
                        ->atPath('miniature')
                        ->addViolation();
                return;
            };

            if ($miniatureWidth != self::MINIATUREWIDTH || $miniatureHeight != self::MINIATUREHEIGHT) {
                $context->buildViolation("Les miniatures doivent être de taille ".self::MINIATUREWIDTH."x".self::MINIATUREHEIGHT." pixels")
                        ->atPath('miniature')
                        ->addViolation();
            }
        }

        if ($pictureUrl != "") {
            try {
                $info = getimagesize($pictureUrl);
                $pictureWidth = $info[0];
                $pictureHeight = $info[1];
            } catch (\Exception $e) {
                $context->buildViolation("Ceci n'est pas une image")
                        ->atPath('picture')
                        ->addViolation();
                return;
            };

            if ($pictureWidth > self::PICTUREWIDTH || $pictureHeight > self::PICTUREHEIGHT) {
                $context->buildViolation("La taille des images ne doit pas dépasser ".self::PICTUREWIDTH."x".self::PICTUREHEIGHT." pixels")
                        ->atPath('picture')
                        ->addViolation();
            }
        }
    }

}
