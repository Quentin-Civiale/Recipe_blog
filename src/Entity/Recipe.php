<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @ORM\Table(name="recipe", indexes={@ORM\Index(columns={"title"}, flags={"fulltext"})})
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", name="published_at")
     * @Groups({"get"})
     */
    private $publishedAt;

    /**
     * @var string|null
     * @ORM\Column
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $note;

    /**
     * @ORM\Column(type="time")
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="time")
     */
    private $cookingTime;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="recipes", cascade={"persist"})
     */
    private $category;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favorite")
     * @Groups({"get"})
     */
    private $favorite;

    /**
     * @ORM\OneToMany(targetEntity=Ingredient::class, mappedBy="recipe", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $ingredients;

    //----------------------------------------------------

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->favorite = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $newIngredient = new Ingredient();
        $this->addIngredient($newIngredient);
    }

    //----------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreparationTime()
    {
        return $this->preparationTime;
    }

    /**
     * @param mixed $preparationTime
     */
    public function setPreparationTime($preparationTime): void
    {
        $this->preparationTime = $preparationTime;
    }

    /**
     * @return mixed
     */
    public function getCookingTime()
    {
        return $this->cookingTime;
    }

    /**
     * @param mixed $cookingTime
     */
    public function setCookingTime($cookingTime): void
    {
        $this->cookingTime = $cookingTime;
    }

    //--------------------------------------------------------

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    //--------------------------------------------------------

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(User $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(User $favorite): self
    {
        $this->favorite->removeElement($favorite);

        return $this;
    }

    //---------------------------------------------------------------------

//    /**
//     * @return Collection|Ingredient[]
//     */
//    public function getIngredients(): Collection
//    {
//        return $this->ingredients;
//    }
//
//    public function addIngredient(Ingredient $ingredient): self
//    {
//        if (!$this->ingredients->contains($ingredient)) {
//            $this->ingredients[] = $ingredient;
//            $ingredient->addRecipe($this);
//        }
//
//        return $this;
//    }
//
//    public function removeIngredient(Ingredient $ingredient): self
//    {
//        if ($this->ingredients->removeElement($ingredient)) {
//            $ingredient->removeRecipe($this);
//        }
//
//        return $this;
//    }

    //-------------------------------------------------------------

    public function addIngredient(Ingredient $ingredient)
    {
        if ($this->ingredients->contains($ingredient)) {
            return;
        }

        $this->ingredients->add($ingredient);

        $ingredient->setRecipe($this);
    }

    public function removeIngredient(Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

//    public function getIngredients(): ArrayCollection
//    {
//        return $this->ingredients;
//    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

}
