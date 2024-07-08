<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    public const REMOVE = 'REMOVE_ARTICLE';
    public const UPDATE = 'UPDATE_ARTICLE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::REMOVE, self::UPDATE])
            && $subject instanceof \App\Entity\Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::REMOVE:
                // logic to determine if the user can EDIT
                // return true or false
                if ($subject->getAuthor()->getUserIdentifier() === $user->getUserIdentifier() || $user->getRoles() == "ROLE_ADMIN") {
                    return true;
                }
                break;

            case self::UPDATE:
                // logic to determine if the user can VIEW
                // return true or false
                if ($subject->getAuthor()->getUserIdentifier() === $user->getUserIdentifier()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
