<?php 
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