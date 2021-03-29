<?php


namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api")
 */
class ApiController
{
    /**
     * @Route("/recipes", name="recipes", methods={"GET"})
     * @param RecipeRepository $recipeRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function recipes(RecipeRepository $recipeRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($recipeRepository->findAll(), "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/recipe/{id}", name="recipe", methods={"GET"})
     * @param Recipe $recipe
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function recipe(Recipe $recipe, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($recipe, "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

}