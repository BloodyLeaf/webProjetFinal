<?php 
/****************************************
   Fichier : UserChecker.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   25 avril 2021    Approuvé par l'équipe
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
21 avril 2021 / Olivier / ajout des conditions dans la méthode checkPostAuth() qui permettent de vérifier lors de l’authentification si l’utilisateur est un administrateur et si c’est sa première connexion
 ****************************************/

namespace App\Security;

use App\Entity\Utilisateur as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserChecker implements UserCheckerInterface
{

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof appUser) {
            return;
        }

        if (!in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Vous devez disposer des droits administrateurs pour accéder au site');
        } else if ($user->getPasswordReset() == true) {
            new RedirectResponse($this->urlGenerator->generate('change_password'));
        }
    }
}

?>