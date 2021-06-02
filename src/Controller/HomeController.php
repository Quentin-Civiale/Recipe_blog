<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findLatestRecipe();

        return $this->render("blog/index.html.twig", [
            'recipes' => $recipe
        ]);
    }

    /**
     * @Route("/les-recettes/les-entrées", name="recipe_starters")
     * @return Response
     */
    public function getRecipeStarters(): Response
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findStarters();

        return $this->render("blog/recipe_category.html.twig", [
            'recipes' => $recipe
        ]);
    }

    /**
     * @Route("/les-recettes/les-plats", name="recipe_meals")
     * @return Response
     */
    public function getRecipeMeals(): Response
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findMeals();

        return $this->render("blog/recipe_category.html.twig", [
            'recipes' => $recipe
        ]);
    }

    /**
     * @Route("/les-recettes/les-desserts", name="recipe_desserts")
     * @return Response
     */
    public function getRecipeDesserts(): Response
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findDesserts();

        return $this->render("blog/recipe_category.html.twig", [
            'recipes' => $recipe
        ]);
    }

    /**
     * @Route("/les-recettes/les-cocktails-&-boissons", name="recipe_drinks")
     * @return Response
     */
    public function getRecipeDrinks(): Response
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findDrinks();

        return $this->render("blog/recipe_category.html.twig", [
            'recipes' => $recipe
        ]);
    }

    /**
     * @Route("/les-recettes/les-sauces", name="recipe_sauces")
     * @return Response
     */
    public function getRecipeSauces(): Response
    {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findSauces();

        return $this->render("blog/recipe_category.html.twig", [
            'recipes' => $recipe
        ]);
    }

}