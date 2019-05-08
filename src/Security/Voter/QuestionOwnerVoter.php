<?php

namespace App\Security\Voter;

use App\Entity\Question;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionOwnerVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['QUESTION_OWNER'])
            && $subject instanceof \App\Entity\Question;
    }

    /**
     * @param string $attribute
     * @param Question $subject
     * @param TokenInterface $token
     * @return bool
     */

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $questionary = $subject->getQuestionary();

        if($questionary->getUser()==$user) {
            return true;
        }

        return false;
    }
}
