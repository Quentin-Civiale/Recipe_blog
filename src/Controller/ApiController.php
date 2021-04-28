<?php


namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return JsonResponse
     */
    public function login(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): JsonResponse
    {
        $username = $request->toArray()["username"];
//        $password = $request->toArray()["password"];
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $username]);

        return new JsonResponse([
                $user->getId()
            ]
        );
    }

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

    /**
     * @Route("/recipe/{id}", name="recipe_update", methods={"PUT"})
     * @param Recipe $recipe
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function updateRecipeByApi(Recipe $recipe, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Recipe::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $recipe]);

        $errors = $validator->validate($recipe);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route("/recipes/{id}/favorite", name="add_favorite_recipe", methods={"PUT"})
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function addFavoriteByApi(Recipe $recipe, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);

        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        $addFavorite = $recipe->addFavorite($this->getUser());

        $entityManager->persist($recipe);
        $entityManager->flush();

        return $this->recipe($recipe, $serializer);
    }

    /**
     * @Route("/recipes/{id}/favorite", name="remove_favorite_recipe", methods={"DELETE"})
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function removeFavoriteByApi(Recipe $recipe, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        $removeFavorite = $recipe->removeFavorite($this->getUser());

        $entityManager->persist($recipe);
        $entityManager->flush();

        return $this->recipe($recipe, $serializer);

    }
}