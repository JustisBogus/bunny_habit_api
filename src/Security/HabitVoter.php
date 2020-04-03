<?php

namespace App\Security;

use App\Entity\Habit;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class HabitVoter extends Voter 
{
    const UPDATE = 'update';
    const RESET = 'reset';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::UPDATE, self::RESET, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Habit) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User) {
            return false;
        }

        /**
         * @var Habit $habit
         */
        $habit = $subject;

        return $habit->getUser()->getId() === $authenticatedUser->getId();
    }
}