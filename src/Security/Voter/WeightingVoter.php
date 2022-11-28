<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class WeightingVoter extends Voter
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
        return in_array($attribute, ['WEIGHTING_EDIT', 'WEIGHTING_VIEW'])
            && $subject instanceof \App\Entity\Weighting;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }
        $profile = $subject->getProfile();
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'WEIGHTING_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                return $user === $profile->getAuthor() || $this->security->isGranted('ROLE_ADMIN');
                break;
            case 'WEIGHTING_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                return $user === $profile->getAuthor() || $profile->getIsPublic() || $this->security->isGranted('ROLE_ADMIN');
                break;
        }

        return false;
    }
}
