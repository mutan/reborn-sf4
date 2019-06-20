<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Liquid", inversedBy="cards")
     */
    private $liquids;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Element", inversedBy="cards")
     */
    private $elements;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $lives;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flying;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $movement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rarity", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rarity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $power_weak;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $power_medium;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $power_strong;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Supertype", inversedBy="cards")
     */
    private $supertypes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Type", inversedBy="cards")
     */
    private $types;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Subtype", inversedBy="cards")
     */
    private $subtypes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flavor;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Artist", inversedBy="cards")
     */
    private $artists;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Edition", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $erratas;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    public function __construct()
    {
        $this->liquids = new ArrayCollection();
        $this->elements = new ArrayCollection();
        $this->supertypes = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->subtypes = new ArrayCollection();
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return Collection|Liquid[]
     */
    public function getLiquids(): Collection
    {
        return $this->liquids;
    }

    public function addLiquid(Liquid $liquid): self
    {
        if (!$this->liquids->contains($liquid)) {
            $this->liquids[] = $liquid;
        }

        return $this;
    }

    public function removeLiquid(Liquid $liquid): self
    {
        if ($this->liquids->contains($liquid)) {
            $this->liquids->removeElement($liquid);
        }

        return $this;
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

    /**
     * @return Collection|Element[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(Element $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLives(): ?int
    {
        return $this->lives;
    }

    public function setLives(int $lives): self
    {
        $this->lives = $lives;

        return $this;
    }

    public function getFlying(): ?bool
    {
        return $this->flying;
    }

    public function setFlying(?bool $flying): self
    {
        $this->flying = $flying;

        return $this;
    }

    public function getMovement(): ?int
    {
        return $this->movement;
    }

    public function setMovement(?int $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getRarity(): ?Rarity
    {
        return $this->rarity;
    }

    public function setRarity(?Rarity $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getPowerWeak(): ?int
    {
        return $this->power_weak;
    }

    public function setPowerWeak(?int $power_weak): self
    {
        $this->power_weak = $power_weak;

        return $this;
    }

    public function getPowerMedium(): ?int
    {
        return $this->power_medium;
    }

    public function setPowerMedium(?int $power_medium): self
    {
        $this->power_medium = $power_medium;

        return $this;
    }

    public function getPowerStrong(): ?int
    {
        return $this->power_strong;
    }

    public function setPowerStrong(?int $power_strong): self
    {
        $this->power_strong = $power_strong;

        return $this;
    }

    /**
     * @return Collection|Supertype[]
     */
    public function getSupertypes(): Collection
    {
        return $this->supertypes;
    }

    public function addSupertype(Supertype $supertype): self
    {
        if (!$this->supertypes->contains($supertype)) {
            $this->supertypes[] = $supertype;
        }

        return $this;
    }

    public function removeSupertype(Supertype $supertype): self
    {
        if ($this->supertypes->contains($supertype)) {
            $this->supertypes->removeElement($supertype);
        }

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
        }

        return $this;
    }

    /**
     * @return Collection|Subtype[]
     */
    public function getSubtypes(): Collection
    {
        return $this->subtypes;
    }

    public function addSubtype(Subtype $subtype): self
    {
        if (!$this->subtypes->contains($subtype)) {
            $this->subtypes[] = $subtype;
        }

        return $this;
    }

    public function removeSubtype(Subtype $subtype): self
    {
        if ($this->subtypes->contains($subtype)) {
            $this->subtypes->removeElement($subtype);
        }

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getFlavor(): ?string
    {
        return $this->flavor;
    }

    public function setFlavor(?string $flavor): self
    {
        $this->flavor = $flavor;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
        }

        return $this;
    }

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): self
    {
        $this->edition = $edition;

        return $this;
    }

    public function getErratas(): ?string
    {
        return $this->erratas;
    }

    public function setErratas(?string $erratas): self
    {
        $this->erratas = $erratas;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
