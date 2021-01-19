<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/les-recettes", name="recipe_list")
     * @return Response
     */
    public function recipeList(): Response
    {
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();

        return $this->render("blog/recipe_list.html.twig", [
            "recipes" => $recipes
        ]);
    }

    /**
     * @Route("/les-recettes/recette-N°{id}", name="show-recipe")
     * @param Recipe $recipe
     * @return Response
     */
    public function show(Recipe $recipe): Response
    {
        return $this->render("blog/show.html.twig", [
            "recipe" => $recipe
        ]);
    }
}