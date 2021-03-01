<?php

namespace App\Entity;

use App\Repository\AverageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AverageRepository::class)
 */
class Average
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Recipe
     * @ORM\OneToOne(targetEntity="App\Entity\Recipe")
     */
    private $recipe;

    /**
     * @ORM\Column(type="decimal", precision=2, scale=1, options={"default" : 0})
     */
    private $average;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $numberScore;

//    ------------------------------------------------------------------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Recipe
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     */
    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function getAverage(): ?string
    {
        return $this->average;
    }

    public function setAverage(?string $average): self
    {
        $this->average = $average;

        return $this;
    }

    public function getNumberScore(): ?int
    {
        return $this->numberScore;
    }

    public function setNumberScore(?int $numberScore): self
    {
        $this->numberScore = $numberScore;

        return $this;
    }
}
