<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get("email")->getData();
            $user = $userRepository->findOneBy(["email" => $email]);

            if($user){
                $token = Uuid::v4()->toRfc4122();
                $user->setResetToken($token);
                $user->setResetTokenExpiresAt((new \DateTime())->modify("+1 hour"));
                
                $entityManagerInterface->flush();

                $resetLink = $this->generateUrl("app_reset_password", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from("MaBoiteMailQuiEstDedieeAEnvoyerLesMails@mail.com")
                    ->to($user->getEmail())
                    ->subject("Reinitialisation de votre mot de passe.")
                    ->text("Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink");
                    $mailer->send($email);
                    $this->addFlash("success", "Un email de réinitialisation vous a été envoyé.");

                    return $this->redirectToRoute("app_login");
            }
            $this->addFlash("error", "Aucun utilisateur trouvé au mail indiqué.");
        }

        return $this->render('reset_password/index.html.twig', [
            "requestForm" => $form->createView(),
        ]);
    }

    #[Route('/reset/password/{token}', name: 'app_reset_password')]
    public function resetPassword(string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $userRepository->findOneBy(["resetToken" => $token]);
        if(!$user || !$user->isResetTokenValid()){
            $this->addFlash("danger", "Ce lien de réinitialisation est invalide ou expiré.");
            return $this->redirectToRoute("app_forgot_password");
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $form->get("plainPassword")->getData();
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);

            $entityManagerInterface->flush();

            $this->addFlash("success", "Votre mot de passe a bien été mis à jour.");
            return $this->redirectToRoute("app_login");
        }
        return $this->render("reset_password/index.html.twig", [
            "resetForm" => $form->createView(),
        ]);
    }
}
