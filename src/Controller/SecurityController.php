<?php

namespace App\Controller;

use App\DataTransferObject\Credentials;
use App\Entity\User;
use App\Form\LoginType;
use App\Form\ForgetPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));

        if ( null !== $authenticationUtils->getLastAuthenticationError(false)) {
            $form->addError(new FormError($authenticationUtils->getLastAuthenticationError()->getMessage()));
        }

        return $this->render("security/login.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @Route("/mot-de-passe-oublié", name="security_forgotten_pass")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param MailerInterface $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function forgottenPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(ForgetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $userRepository->findOneByEmail($data['email']);

            if (!$user) {
                $this->addFlash('warning', 'Cette adresse mail n\'existe pas');

                return $this->redirectToRoute('security_login');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetPassword($token);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {

                $this->addFlash('warning', 'Une erreur est survenue : '. $e->getMessage());

                return $this->redirectToRoute('security_login');
            }

            $url = $this->generateUrl('security_reset_pass', ['token'=> $token],
            UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new Email())
                ->from('no-reply@recettes-lauramay.com')
                ->to($user->getEmail())
                ->subject('Les Recettes de Lauramay - Réinitialisation du mot de passe')
                ->html(
                    "<p>Bonjour,</p><p>Une demande de réinitialisation de mot de passe a été effectuée pour le site
                    Les Recettes de Lauramay.</p><p>Veuillez cliquer sur le lien suivant : <a href='". $url ."'>Réinitialiser mon mot de passe</a></p>"
                )
            ;

            $mailer->send($message);

            $this->addFlash('message', 'Un e-mail de réinitialisation de mot de passe vous a été envoyé');

            return $this->redirectToRoute('security_login');
        }

        return  $this->render('security/forgotten_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/réinitialisation-du-mot-de-passe/{token}", name="security_reset_pass")
     * @param $token
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_password' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('security_login');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setResetPassword(null);

            $user->setPassword($passwordEncoder->encodePassword($user, $form->get("plainPassword")->getData()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('security_login');
        } else {

            return $this->render('security/reset_password.html.twig', [
                'token' => $token,
                'form' => $form->createView()
            ]);
        }

    }
}