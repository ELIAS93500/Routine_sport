<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\Transport\TransportInterface;

class SecurityController extends AbstractController
{
    // Action pour gérer la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirige l'utilisateur vers la page cible s'il est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('myaccount_index');
        }

        // Récupère les erreurs de connexion, le cas échéant
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Rend la vue de la page de connexion en passant le dernier nom d'utilisateur et les erreurs
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Action pour gérer la déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être laissée vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // Action pour gérer la réinitialisation du mot de passe
    #[Route('/reset/password', name: 'reset_password')]
    public function reset(Request $request, TransportInterface $mailer, UserRepository $repository): Response
    {
        if (!empty($_POST)) {
            // Récupère l'adresse e-mail soumise par le formulaire
            $email = $request->request->get('email');
            // Recherche l'utilisateur associé à l'adresse e-mail
            $user = $repository->findOneBy(['email' => $email]);

            // Vérifie si l'utilisateur existe
            if ($user) {
                // Crée un e-mail de réinitialisation de mot de passe
                $email = (new TemplatedEmail())
                    ->from('routinesport@coorg.com')
                    ->to($email)
                    ->subject('Récupération de mot de passe')
                    ->htmlTemplate('email/reset_password.html.twig')
                    ->context([
                        'user' => $user
                    ]);

                // Envoie l'e-mail de réinitialisation
                $mailer->send($email);
                $this->addFlash('success', 'Un mail de réinitialisation viens de vous être transmis');
            } else {
                // Si aucun compte n'est associé à cette adresse e-mail, affiche un message d'erreur
                $this->addFlash('danger', 'Aucun compte à cette adresse mail');
                return $this->redirectToRoute('reset_password');
            }
        }

        // Affiche le formulaire de réinitialisation de mot de passe à l'utilisateur
        return $this->render('security/forgot_password.html.twig', []);
    }

    // Action pour définir un nouveau mot de passe après la réinitialisation
    #[Route('/password/new/{id}', name: 'new_password')]
    public function new_password(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, $id): Response
    {
        // En théorie, un champ token devrait exister en BDD, un token qui aurait été généré à la demande de réinitialisation ainsi qu'une date d'expiration.
        // Ensuite, la recherche de l'utilisateur aurait été findOneBy(['token' => $token]);
        if (!empty($_POST)) {
            // Recherche l'utilisateur par son ID
            $user = $userRepository->find($id);
            // Hash le nouveau mot de passe
            $mdp = $hasher->hashPassword($user, $request->request->get('password'));
            // Définit le nouveau mot de passe pour l'utilisateur
            $user->setPassword($mdp);
            // Persiste les modifications en base de données
            $manager->persist($user);
            // Exécute les opérations enregistrées
            $manager->flush();
            // Affiche un message de succès et redirige vers la page de connexion
            $this->addFlash('info', 'Mot de passe réinitialisé, connectez-vous à présent');
            return $this->redirectToRoute('app_login');
        }

        // Affiche le formulaire de définition du nouveau mot de passe
        return $this->render('security/new_password.html.twig', [
            'id' => $id
        ]);
    }
}
