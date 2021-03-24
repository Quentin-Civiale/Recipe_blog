<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $proportion;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="ingredients", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $recipe;

//    public function __construct()
//    {
//        $this->recipe = new ArrayCollection();
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProportion(): ?string
    {
        return $this->proportion;
    }

    public function setProportion(?string $proportion): self
    {
        $this->proportion = $proportion;

        return $this;
    }

//    /**
//     * @return Collection|Recipe[]
//     */
//    public function getRecipe(): Collection
//    {
//        return $this->recipe;
//    }
//
//    public function addRecipe(Recipe $recipe): self
//    {
//        if (!$this->recipe->contains($recipe)) {
//            $this->recipe[] = $recipe;
//        }
//
//        return $this;
//    }
//
//    public function removeRecipe(Recipe $recipe): self
//    {
//        $this->recipe->removeElement($recipe);
//
//        return $this;
//    }


    public function setRecipe(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    public function getRecipe()
    {
        return $this->recipe;
    }
}
