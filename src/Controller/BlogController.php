<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeSearchType;
use App\Form\RecipeType;
use App\Security\Voter\RecipeVoter;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

//        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();

        $form = $this->createForm(RecipeSearchType::class);
        $search = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on recherche les recettes correspondantes aux mots clés
            $recipes = $this->getDoctrine()->getRepository(Recipe::class)->search(
                $search->get('title')->getData(),
                $search->get('category')->getData()
            );
        }

        return $this->render("blog/recipe_list.html.twig", [
            "recipes" => $recipes,
            "pages" => $pages,
            "page" => $page,
            "limit" => $limit,
            "range" => $range,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/publier-recette", name="blog_create")
     * @param Request $request
     * @param UploaderInterface $uploader
     * @return Response
     */
    public function create(Request $request, UploaderInterface $uploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $recipe = new Recipe();
        $recipe->setUser($this->getUser());

        $form = $this->createForm(RecipeType::class, $recipe, [
            "validation_groups" => ["Default", "create"]
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();

            $recipe->setImage($uploader->upload($file));

            $this->getDoctrine()->getManager()->persist($recipe);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('recipeSuccess', 'Recette créée avec succès !');

            return $this->redirectToRoute("blog_read", ["id" => $recipe->getId()]);
        }

        return $this->render("blog/create.html.twig", [
            "form" => $form->createView()
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
     * @Route("/modifier-recette N°{id}", name="blog_update")
     * @param Request $request
     * @param Recipe $recipe
     * @param UploaderInterface $uploader
     * @return Response
     */
    public function update(Request $request, Recipe $recipe, UploaderInterface $uploader): Response
    {
        $this->denyAccessUnlessGranted(RecipeVoter::EDIT, $recipe);

        $form = $this->createForm(RecipeType::class, $recipe)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();

            if ($file !== null) {
                $recipe->setImage($uploader->upload($file));
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("blog_read", ["id" => $recipe->getId()]);
        }

        return $this->render("blog/update.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimer-recette N°{id}", name="blog_delete")
     * @param Recipe $recipe
     * @return Response
     */
    public function delete(Recipe $recipe): Response
    {
        $recipeDelete = $this->getDoctrine()->getManager();
        $recipeDelete->remove($recipe);
        $recipeDelete->flush();

        $this->addFlash('recipeDelete', 'Recette supprimée avec succès');

        return $this->redirectToRoute('recipe_list');
    }
}