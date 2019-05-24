<?php

namespace App\Security\Voter;

use App\Entity\GameSession;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordIntroducedVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['GAME_DISPONIBLE'])
            && $subject instanceof \App\Entity\GameSession;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        $user = $token->getUser();
        $gameusers = $subject->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        foreach( $gameusers as $user2) {
            if ($user2 == $user) {

                return true;
                break;
            }
        }

        return false;
    }
}
