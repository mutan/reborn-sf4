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

    public function __construct()
    {
        $this->liquids = new ArrayCollection();
        $this->elements = new ArrayCollection();
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
}
