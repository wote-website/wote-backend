<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['PROFILE_EDIT', 'PROFILE_VIEW'])
            && $subject instanceof \App\Entity\Profile;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PROFILE_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                return $user === $subject->getAuthor() || $this->security->isGranted('ROLE_ADMIN');
                break;
            case 'PROFILE_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                return $user === $subject->getAuthor() || $subject->getIsPublic() || $this->security->isGranted('ROLE_ADMIN');
                break;
        }

        return false;
    }
}
