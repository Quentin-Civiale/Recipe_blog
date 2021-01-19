<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function recipeList(Request $request): Response
    {
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 9);

        /** @var Paginator $recipes */
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->getPaginatedRecipes(
            $page,
            $limit
        );

        $pages = ceil($recipes->count() / $limit);

        $range = range(
            max( $page - 3, 1),
            min( $page + 3, $pages)
        );

        return $this->render("blog/recipe_list.html.twig", [
            "recipes" => $recipes,
            "pages" => $pages,
            "page" => $page,
            "limit" => $limit,
            "range" => $range
        ]);
    }

    /**
     * @Route("/les-recettes/recette-N°{id}", name="blog_read")
     * @param Recipe $recipe
     * @return Response
     */
    public function read(Recipe $recipe): Response
    {
        return $this->render("blog/read.html.twig", [
            "recipe" => $recipe
        ]);
    }

    /**
     * @Route("/publier-recette", name="blog_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($recipe);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("blog_read", ["id" => $recipe->getId()]);
        }

        return $this->render("blog/create.html.twig", [
            "form" => $form->createView()
        ]);
    }
}